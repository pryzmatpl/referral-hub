<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userweight extends Model
{

  protected $table = 'userweights';

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
			 'userid',
			 'keywords'
			 ];

     protected $casts = [
        'keywords' => 'array'
    ];
  
}