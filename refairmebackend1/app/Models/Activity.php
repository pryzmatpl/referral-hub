<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

/**
* 
*/
class Activity extends Model
{
	
	protected $table = 'activities';

	public $timestamp;
	public $request;

	protected $fillable = [
			       'request'
			       ];
	
	public function setRequest($req){
	  $this->update(['request' => $req]);
	}

	public function getRequest($id){
	  return $this->request;
	}
}
