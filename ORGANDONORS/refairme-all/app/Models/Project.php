<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

  ///There is no contractType
  //Not anymore
  //There never was
  //Never will be
  //except in the db
  protected $table = 'projects';

  public $id;
  public $staff;
  public $name;
  public $description;
  public $stage;
  public $stack;
  public $methodology;
  public $breakdown;
  public $posterId;
  public $companyId;
  
  protected $fillable = [
			 'staff',
			 'companyId',
			 'name',
			 'description',
			 'stage',
			 'stack',
			 'methodology',
			 'breakdown',
			 'posterId'
			 ];
  
}