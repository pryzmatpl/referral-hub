<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userexp extends Model
{
    protected $table = 'userexp';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 
        'name', 
        'role', 
        'responsibilities', 
        'current_job', 
        'start', 
        'end', 
        'years',
        'salary'
    ];

    protected $casts = [
        'current_job' => 'boolean',
        'start' => 'date',
        'end' => 'date',
        'years' => 'integer',
        'salary' => 'integer',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
