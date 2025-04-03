<?php

namespace App\Models;

use App\Enums\ReferralStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Referral
 *
 * Represents a referral record. Uses Laravel’s native relationship
 * methods and casts the status to a modern PHP enum for better type safety.
 */
final class Referral extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    /**
     * The table associated with the model.
     */
    protected $table = 'jobrefs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'jobid',
        'users_id',
        'email',
        'name',
        'status',
        'hash',
        'created_at',
        'updated_at',
        'referrer_id'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => ReferralStatus::class,
    ];

    /**
     * Get the associated job description.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'jobid', 'id')
            ->with('Company');
    }

    /**
     * Get the associated user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }
}
