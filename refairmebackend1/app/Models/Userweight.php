<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userweight extends Model
{

  protected $table = 'user_weights';

  protected $fillable = [
    'weight_one',
    'weight_two',
    'weight_three',
    'weight_four',
    'weight_five',
    'weight_six',
    'weight_seven',
    'weight_eight',
    'weight_nine',
    'weight_ten',
    'weight_eleven',
    'user_id',
    'keywords'
];


     protected $casts = [
        'keywords' => 'array'
    ];
  
}