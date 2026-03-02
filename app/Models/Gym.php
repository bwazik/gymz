<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\City $city
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkoutIntent> $workoutIntents
 */
class Gym extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function workoutIntents(): HasMany
    {
        return $this->hasMany(WorkoutIntent::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes & Methods
    |--------------------------------------------------------------------------
    */
}
