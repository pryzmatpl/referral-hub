<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    protected $table = 'jobs_apply';

    protected $appends = array('job', 'user');

    protected $fillable = [
        'id',
        'jobs_id',
        'users_id',
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