<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Gender;
use App\Enums\UserLevel;
use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property bool $is_admin
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property \App\Enums\Gender|null $gender
 * @property \Illuminate\Support\Carbon|null $dob
 * @property \App\Enums\UserLevel $level
 * @property int $glutes_balance
 * @property int $reliability_score
 * @property string|null $image_path
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkoutIntent> $workoutIntents
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkoutRequest> $workoutRequests
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkoutSession> $workoutSessionsAsUserA
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkoutSession> $workoutSessionsAsUserB
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GlutesTransaction> $glutesTransactions
 */
#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
        'gender',
        'dob',
        'level',
        'glutes_balance',
        'reliability_score',
        'image_path',
        'google_id',
        'is_onboarded',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'gender' => Gender::class,
            'dob' => 'date',
            'level' => UserLevel::class,
            'glutes_balance' => 'integer',
            'reliability_score' => 'integer',
            'is_onboarded' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function workoutIntents(): HasMany
    {
        return $this->hasMany(WorkoutIntent::class);
    }

    public function workoutRequests(): HasMany
    {
        return $this->hasMany(WorkoutRequest::class, 'sender_id');
    }

    public function workoutSessionsAsUserA(): HasMany
    {
        return $this->hasMany(WorkoutSession::class, 'user_a_id');
    }

    public function workoutSessionsAsUserB(): HasMany
    {
        return $this->hasMany(WorkoutSession::class, 'user_b_id');
    }

    public function glutesTransactions(): HasMany
    {
        return $this->hasMany(GlutesTransaction::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes & Methods
    |--------------------------------------------------------------------------
    */

    public function scopeMales(Builder $query): Builder
    {
        return $query->where('gender', Gender::Male);
    }

    public function scopeFemales(Builder $query): Builder
    {
        return $query->where('gender', Gender::Female);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }
}
