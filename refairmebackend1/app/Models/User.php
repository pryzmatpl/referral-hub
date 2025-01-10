<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

  protected $table = 'users';

  protected $appends = ['roles'];

  protected $fillable = [
			 'activ',
			 'first_name',
			 'last_name',
			 'email',
			 'name',
			 'exp',
			 'password',
			 'activ_code',
			 'group_id',
			 'cvadded',
			 'skills',
			 'scheduling',
			 'expected_salary',
			 'notice_period',
       'current_role'
			 ];

  protected $casts = [
		      'skills' => 'array',
		      'exp' => 'array'
		      ];
	
  public function jobs() {
    return $this->hasMany('App\Models\Jobdesc');
  }

  public function companies() {
    return $this->hasMany('App\Models\Companies');
  }

  public function skills() {
    return $this->belongsToMany('App\Models\Tag')->where('type', '=', 'skills')->withPivot('exp', 'years');
  }

  public function weights() {
    return $this->hasOne('App\Models\Userweight', 'userid')->orderBy('id', 'DESC');
  }

  public function getRolesAttribute() {
    $roles = [];
    if ($this->is_candidate) $roles[] = 'candidate';
    if ($this->is_recruiter) $roles[] = 'recruiter';
    if ($this->is_company) $roles[] = 'company';
    if ($this->is_admin) $roles[] = 'admin';
    return $roles;
  }



  public function setPassword($password)
  {
    $this->update([
		   'password' => password_hash($password,PASSWORD_DEFAULT)
		   ]);
  }

  public function setGroupid($groupd)
  {
    $this->group_id = $groupd;
  }

  public function setFirstName($firstName)
  {
    $this->first_name = trim($firstName);
  }

  public function getFirstName()
  {
    return $this->first_name;
  }

  public function setLastName($lastName)
  {
    $this->last_name = trim($lastName);
  }

  public function getLastName()
  {
    return $this->last_name;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getEmails()
  {
    return $this->email;
  }

  public function getFullName()
  {
    return "$this->first_name $this->last_name";
  }

	

  public function getGroupid()
  {
    return $this->group_id;
  }
	
  public function getEmailVariables()
  {
    return [
	    'full_name' => $this->getFullName(),
	    'email' => $this->getEmail(),
	    ];
  }
}