<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    protected $table = 'appointments';

    protected $fillable =[
        'candidate_id',
        'recruiter_id',
        'date',
        'duration',
        'state'
    ];

    protected $casts=['state'=>'array'];

}