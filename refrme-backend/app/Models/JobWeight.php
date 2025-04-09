<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobWeight extends Model
{

    protected $table = 'jobweights';

    //Weights are JSON! From the classifier!!!
    protected $fillable = [
        'weights',
        'jobid',
        'keywords'
    ];
}