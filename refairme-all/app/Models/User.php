<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

	protected $table = 'users';

	public $first_name;

	public $last_name;

	public $cvadded;

	public $email;

	public $group_id;

	protected $fillable = [
		'email',
		'name',
		'password',
		'activ_code',
		'group_id',
		'cvadded'
	];

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