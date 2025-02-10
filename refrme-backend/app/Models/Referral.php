<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    const VALUE_STATUS_NEW = 'new';
    const VALUE_STATUS_PENDING = 'pending';
    const VALUE_STATUS_ACCEPTED = 'accepted';
    const VALUE_STATUS_REJECTED = 'rejected';

    protected $table = 'jobs_referral';

    //protected $attributes = ['job', 'user'];

    protected $appends = array('job', 'user');

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

    public function getJobAttribute() {
        return $this->job;
    }

    public function setJobAttribute($value) {
        $this->job = $value;
    }

    public function getUserAttribute() {
        return $this->user;
    }

    public function setUserAttribute($value) {
        $this->user = $value;
    }

    public function job() {
        return $this->hasOne('App\Models\Jobdesc', 'id', 'jobs_id')->with('Company');
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'users_id');
    }
}