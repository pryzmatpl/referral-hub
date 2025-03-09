<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $keywords
 */
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

    public function project(): BelongsTo
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'companyId');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    public function referral(): BelongsTo
    {
        return $this->belongsTo('App\Models\Referral');
    }

    public static function InvalidJob(): Job
    {
        return new Job(['type'=>'empty']);
    }
}