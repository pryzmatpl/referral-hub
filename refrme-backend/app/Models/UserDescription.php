<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDescription extends Model
{
    protected $table = 'userdescs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'keywords',
        'skills',
        'notice_period',
        'availability',
        'expected_salary',
        'job_status',
    ];

    protected $casts = [
        'keywords' => 'array',
        'skills' => 'array',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
