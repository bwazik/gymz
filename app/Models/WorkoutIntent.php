<?php

namespace App\Models;

use App\Enums\IntentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $user_id
 * @property int $gym_id
 * @property int $workout_target_id
 * @property \Illuminate\Support\Carbon $start_time
 * @property bool $has_invitation
 * @property string|null $note
 * @property \App\Enums\IntentStatus $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Gym $gym
 * @property-read \App\Models\WorkoutTarget $workoutTarget
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkoutRequest> $workoutRequests
 * @property-read \App\Models\WorkoutSession|null $workoutSession
 */
class WorkoutIntent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'gym_id',
        'workout_target_id',
        'start_time',
        'has_invitation',
        'note',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'has_invitation' => 'boolean',
        'status' => IntentStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function workoutTarget(): BelongsTo
    {
        return $this->belongsTo(WorkoutTarget::class);
    }

    public function workoutRequests(): HasMany
    {
        return $this->hasMany(WorkoutRequest::class, 'intent_id');
    }

    public function workoutSession(): HasOne
    {
        return $this->hasOne(WorkoutSession::class, 'intent_id');
    }
}
