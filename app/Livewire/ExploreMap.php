<?php

namespace App\Livewire;

use App\Actions\Workout\SendWorkoutRequest;
use App\Models\City;
use App\Models\Gym;
use App\Models\WorkoutCategory;
use App\Models\WorkoutIntent;
use App\Models\WorkoutRequest;
use App\Traits\Livewire\WithRateLimiting;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class ExploreMap extends Component
{
    use WithToast, WithRateLimiting;

    public ?int $selectedGymId = null;
    public bool $showGymPanel = false;
    public ?int $selectedCategoryId = null;
    public ?int $currentCityId = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->currentCityId = $user?->current_city_id ?? Session::get('current_city_id');
    }

    #[On('city-updated')]
    public function updateCity(int $cityId, float $lat, float $lng): void
    {
        $this->currentCityId = $cityId;
        $this->closePanel();

        $gymsMapData = $this->buildGymsMapData($this->getGymsWithIntentCounts());
        $this->dispatch('gyms-updated', gyms: $gymsMapData);
        $this->dispatch('fly-to-city', lat: $lat, lng: $lng);
    }

    public function selectGym(int $gymId): void
    {
        $this->selectedGymId = $gymId;
        $this->showGymPanel = true;
    }

    public function closePanel(): void
    {
        $this->showGymPanel = false;
        $this->selectedGymId = null;
    }

    public function filterCategory(?int $categoryId): void
    {
        $this->selectedCategoryId = $categoryId;
        $this->closePanel();

        $gymsMapData = $this->buildGymsMapData($this->getGymsWithIntentCounts());
        $this->dispatch('gyms-updated', gyms: $gymsMapData);
    }

    public function sendRequest(int $intentId, SendWorkoutRequest $action): void
    {
        if ($this->isRateLimited('send-request', 5, 30)) {
            return;
        }

        try {
            $message = $action->execute($intentId);
            $this->dispatch('request-sent');
            $this->toastSuccess($message);
        } catch (\Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    public function render()
    {
        $user = Auth::user();
        $gyms = $this->getGymsWithIntentCounts();
        $gymsMapData = $this->buildGymsMapData($gyms);

        // Intents for the selected gym
        $selectedGymIntents = collect();
        $sentRequestIntentIds = [];
        $selectedGym = null;

        if ($this->selectedGymId) {
            $selectedGym = $gyms->firstWhere('id', $this->selectedGymId);

            $intentQuery = WorkoutIntent::with(['user', 'gym', 'workoutTarget.workoutCategory'])
                ->active()
                ->upcoming()
                ->where('gym_id', $this->selectedGymId);

            if ($this->selectedCategoryId) {
                $intentQuery->whereHas('workoutTarget', fn($q) => $q->where('workout_category_id', $this->selectedCategoryId));
            }

            if ($user && $user->gender) {
                $intentQuery->whereHas('user', fn($q) => $q->where('gender', $user->gender));
                $intentQuery->where('user_id', '!=', $user->id);
            }

            $selectedGymIntents = $intentQuery->orderBy('start_time', 'asc')->get();

            if ($user) {
                $sentRequestIntentIds = WorkoutRequest::where('sender_id', $user->id)
                    ->pluck('intent_id')
                    ->toArray();
            }
        }

        $currentCity = City::find($this->currentCityId);
        $mapCenter = $currentCity && $currentCity->latitude && $currentCity->longitude
            ? ['lat' => (float) $currentCity->latitude, 'lng' => (float) $currentCity->longitude]
            : ['lat' => 30.4589107, 'lng' => 31.1472219]; // Fallback if no city found or coords unset

        return view('livewire.explore-map', [
            'gymsMapData' => $gymsMapData,
            'mapCenter' => $mapCenter,
            'selectedGymIntents' => $selectedGymIntents,
            'sentRequestIntentIds' => $sentRequestIntentIds,
            'selectedGym' => $selectedGym,
            'categories' => WorkoutCategory::orderBy('name')->get(),
        ]);
    }

    private function getGymsWithIntentCounts(): Collection
    {
        $user = Auth::user();

        return Gym::query()
            ->when($this->currentCityId, fn($q) => $q->where('city_id', $this->currentCityId))
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->active()
            ->withCount(['workoutIntents' => function ($query) use ($user) {
                $query->active()->upcoming();
                if ($user) {
                    if ($user->gender) {
                        $query->whereHas('user', fn($q) => $q->where('gender', $user->gender));
                    }
                    $query->where('user_id', '!=', $user->id);
                }
                if ($this->selectedCategoryId) {
                    $query->whereHas('workoutTarget', fn($q) => $q->where('workout_category_id', $this->selectedCategoryId));
                }
            }])
            ->get();
    }

    private function buildGymsMapData(Collection $gyms): array
    {
        return $gyms->map(fn(Gym $gym) => [
            'id' => $gym->id,
            'name' => $gym->name,
            'lat' => (float) $gym->latitude,
            'lng' => (float) $gym->longitude,
            'count' => $gym->workout_intents_count,
            'logo' => $gym->logo_path
                ? (str_starts_with($gym->logo_path, 'http') ? $gym->logo_path : asset('storage/' . $gym->logo_path))
                : null,
        ])->values()->toArray();
    }
}
