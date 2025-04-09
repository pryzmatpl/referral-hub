<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

  protected $table = 'companies';

/*  public $id;
  public $companyName;
  public $description;
  public $posterId;*/

  protected $fillable = [
			 'name',
			 'description',
			 'posterId'
			 ];

  public function jobs() {
      return $this->hasMany('App\Models\JobDesc');
  }

}