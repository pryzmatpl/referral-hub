<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model {
    protected $table = 'jobs';

    
    protected $fillable = [
        'id',
        'title',
        'exp',
        'fund',
        'relocation',
        'remote',
        'keywords',
        'location',
        'travelPercentage',
        'remotePercentage',
        'relocationPackage',
        'projectId',
        'companyId',
        'other',
        'contractType',
        'currency',
        'description',
        'bounty',
        'hash',
        'musthave',
        'nicetohave',
        'essentials',
        'specs',
    ];

    protected $casts = [
        'fund' => 'array',
        'keywords' => 'array',
        'contractType' => 'array',

    ];

    public function project() {
        return $this->belongsTo('App\Models\Project');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'companyId');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function referral() {
        return $this->belongsTo('App\Models\Referral');
    }

}