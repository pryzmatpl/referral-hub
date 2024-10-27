<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobdesc extends Model {
    protected $table = 'jobdescs';

    protected $fillable = [
        'id',
        'title',
        'exp',
        'fund',
        'relocation',
        'remote',
        'keywords',
        'location',
        'travel_percentage',
        'remote_percentage',
        'relocation_package',
        'project_id',
        'company_id',
        'other',
        'contract_type',
        'currency',
        'description',
        'bounty',
        'hash',
        'skills',
        'skills_nice',
        'frameworks_must',
        'frameworks_nice',
        'methodologies_must',
        'methodologies_nice'
    ];

    protected $casts = [
        'exp' => 'array',
        'fund' => 'array',
        'currency' => 'array',
        'contract_type' => 'array',
        'skills' => 'array',
        'skills_nice' => 'array',
        'frameworks_must' => 'array',
        'frameworks_nice' => 'array',
        'methodologies_must' => 'array',
        'methodologies_nice' => 'array'
    ];

    public function project() {
        return $this->belongsTo('App\Models\Project');
    }

    public function company() {
        return $this->belongsTo('App\Models\Company');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function referral() {
        return $this->belongsTo('App\Models\Referral');
    }

}