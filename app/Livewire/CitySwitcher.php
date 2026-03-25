<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CitySwitcher extends Component
{
    public $cities;
    public $selectedCityId;

    public function mount()
    {
        $this->cities = City::where('is_active', true)->orderBy('name')->get();
        
        $user = Auth::user();
        if ($user && $user->current_city_id) {
            $this->selectedCityId = $user->current_city_id;
        } else {
            $this->selectedCityId = Session::get('current_city_id', $this->cities->first()->id ?? null);
            // If logged in but no city set, save the default one to DB
            if ($user && $this->selectedCityId && !$user->current_city_id) {
                $user->update(['current_city_id' => $this->selectedCityId]);
            }
        }
    }

    public function updatedSelectedCityId($value)
    {
        if (empty($value)) return;
        
        Session::put('current_city_id', $value);
        
        if (Auth::check()) {
            Auth::user()->update(['current_city_id' => $value]);
        }

        $city = $this->cities->firstWhere('id', $value);
        
        if ($city) {
            $this->dispatch('city-updated', cityId: $city->id, lat: (float) $city->latitude, lng: (float) $city->longitude);
        }
    }

    public function render()
    {
        return view('livewire.city-switcher');
    }
}
