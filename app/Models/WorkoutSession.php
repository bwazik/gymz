<?php

namespace App\Models;

use App\Enums\SessionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $intent_id
 * @property int $user_a_id
 * @property int $user_b_id
 * @property string $qr_token
 * @property \Illuminate\Support\Carbon|null $scanned_at
 * @property \App\Enums\SessionStatus $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\WorkoutIntent $workoutIntent
 * @property-read \App\Models\User $userA
 * @property-read \App\Models\User $userB
 */
class WorkoutSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'intent_id',
        'user_a_id',
        'user_b_id',
        'qr_token',
        'scanned_at',
        'status',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'status' => SessionStatus::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function workoutIntent(): BelongsTo
    {
        return $this->belongsTo(WorkoutIntent::class, 'intent_id');
    }

    public function userA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_a_id');
    }

    public function userB(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_b_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes & Methods
    |--------------------------------------------------------------------------
    */

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', SessionStatus::Scheduled);
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', SessionStatus::InProgress);
    }

    public function isActive(): bool
    {
        return $this->status === SessionStatus::Scheduled || $this->status === SessionStatus::InProgress;
    }
}
