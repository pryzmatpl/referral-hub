<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userweight extends Model
{

  protected $table = 'userweights';

  public $aone;
  public $atwo;
  public $athree;
  public $afour;
  public $afive;
  public $asix;
  public $aseven;
  public $aeight;
  public $anine;
  public $aten;
  public $aeleven;
  public $userid;
  public $keywords;
  
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

  
  
}