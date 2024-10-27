<?php
namespace App\Auth;

use App\Models\User;
use App\Middleware\PrizmMiddleware;
/**
 *
 */
class Auth
{
  public function user()
  {
    return User::find(isset($_SESSION['user']) ? $_SESSION['user'] : 0);
  }

  public function check()
  {
    //Strong requirement - need means to analyze tokens
    if(isset($_SESSION['creds']['token'])){
      return true;
    }else{
	return false;
    }
  }
  
  public function attempt($email,$password)
  {
    $user = User::where('email',$email)->first();

    if (!$user) {
      return false;
    }
        
    if ($user->activ == 0){ // <-- That need we
      return false;
    }
	
    if (password_verify($password,$user->password)) {
      $_SESSION['user'] = $user->email;
      return true;
    }

    return false;
  }

  public function logout()
  {
    unset($_SESSION['user']);
  }
}