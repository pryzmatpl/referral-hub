<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OA2Clients extends Model
{
  //This model is required to allow logins via OAuth2
  
  protected $table = 'oauth_clients';
  
  public $client_id;
  public $client_secret;
  public $redirect_uri;
  public $grant_types;
  public $scope;
  public $user_id;

  protected $fillable =[
			'client_id',
			'client_secret',
			'redirect_uri',
			'grant_types',
			'scope',
			'user_id'
			];
  
}