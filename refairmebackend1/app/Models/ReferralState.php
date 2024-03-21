<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralState extends Model
{
    const VALUE_STATUS_NEW = 'new';
    const VALUE_STATUS_PENDING = 'pending';
    const VALUE_STATUS_ACCEPTED = 'accepted';
    const VALUE_STATUS_REJECTED = 'rejected';

    protected $table = 'jobs_referral_state';

    protected $fillable = [
        'id',
        'jobs_referral_id',
        'state',
        'comment',
        'created_at',
        'updated_at',
    ];
}