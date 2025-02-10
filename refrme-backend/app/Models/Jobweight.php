<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobweight extends Model
{

  protected $table = 'jobweights';

  
  protected $fillable = [
			 'aone',
			 'atwo',
			 'athree',
			 'afour',
			 'afive',
			 'asix',
			 'aseven',
			 'aeight',
			 'anine',
			 'aten',
			 'aeleven',
			 'job_id',
			 'keywords'
			 ];

  
  
}