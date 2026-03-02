<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $intent_id
 * @property int $sender_id
 * @property \App\Enums\RequestStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\WorkoutIntent $workoutIntent
 * @property-read \App\Models\User $sender
 */
class WorkoutRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'intent_id',
        'sender_id',
        'status',
    ];

    protected $casts = [
        'status' => RequestStatus::class,
    ];

    public function workoutIntent(): BelongsTo
    {
        return $this->belongsTo(WorkoutIntent::class, 'intent_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
