<?php

namespace App\Models;

use App\Enums\ReferralStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Referral
 *
 * Represents a referral record. Uses Laravel’s native relationship
 * methods and casts the status to a modern PHP enum for better type safety.
 */
final class Referral extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'jobs_referral';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'jobs_id',
        'users_id',
        'email',
        'name',
        'status',
        'hash',
        'created_at',
        'updated_at',
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
    public function job(): HasOne
    {
        return $this->hasOne(JobDesc::class, 'id', 'jobs_id')
            ->with('Company');
    }

    /**
     * Get the associated user.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }
}
