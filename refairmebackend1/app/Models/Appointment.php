<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
  protected $table = 'appointments';

  protected $fillable =[
			'candidate_id',
			'recruiter_id',
			'appointment',
			'status'
			];
  
  protected $casts=['state'=>'array',
		    'appointment'=>'array'];
  
}