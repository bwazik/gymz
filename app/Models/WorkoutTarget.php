<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $workout_category_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\WorkoutCategory $workoutCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkoutIntent> $workoutIntents
 */
class WorkoutTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_category_id',
        'name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function workoutCategory(): BelongsTo
    {
        return $this->belongsTo(WorkoutCategory::class);
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
