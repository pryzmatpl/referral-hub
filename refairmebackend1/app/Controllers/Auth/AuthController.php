<?php
namespace App\Controllers\Auth;
use Nette\Mail\Message;

use Illuminate\Database\Eloquent\Model;

use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

use App\Models\User;
use App\Models\Product;
use App\Models\OA2Clients;
use App\Models\UserPermission;
use App\Models\Group;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use Litipk\Jiffy\UniversalTimestamp;
//Adding PSR classes to boost our auth controller
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carlosocarvalho\SimpleInput\Input\Input;

const ORIGIN = "prizm";
const SEPARATOR = "~";
const CHILDSEPARATOR = ":";

class AuthController extends Controller
{
  public function getSignOut($request,$response)
  {
    $this->auth->logout();
    return $response->withJson(array('state'=>'success',
				     'message'=>'You have been logged out'));
  }

  public function getSignIn($request,$response)
  {

    $this->flash->addMessage('messages','Hello, please login.');

    $thistate =  json_encode('help');
    $chuj = json_encode($this->flash->getMessages(),true);

    return $this->view->render($response,
			       'app-boot.twig',
			       array('state'=>$thistate,
				     'messages'=> $chuj )
			       );
  }

    public function postSignIn($request, $response)
    {
      try{
        //Attempt authentication
        $payload = $request->getQueryParams();

        $auth = $this->auth->attempt($payload['email'],
				     $payload['password']);


        if (!$auth) {
	  $thistate = "Validation failed - you stated a malformed email address or a wrong password for " . $request->getParam('email');

            return $response->withHeader('Access-Control-Allow-Origin', '*')
                ->withJson(array('message' => $thistate,
                    'state' => 'error',
                    'auth' => false));

        } else {
            $user = User::where('email', '=', $request->getParam('email'))->first();
            //Create cornerstone for hashgraph
            $origin = env('HASH_BASE');

            //Get timestamp for now
            $now = UniversalTimestamp::now();

	    $roles['developer'] = $user->is_developer;
	    $roles['admin'] = $user->is_admin;
	    $roles['recruiter'] = $user->is_recruiter;
	    $roles['candidate'] = $user->is_candidate;

	    $roles = json_encode($roles, true);

            //Initialize hashgraph with timestamp and data for frontend
            $cornerstone = $this->iwahash($origin, "TIMESTAMP", $now);
            $cornerstone = $this->iwahash($cornerstone, "ORIGIN", $origin);
            $cornerstone = $this->iwahash($cornerstone, "EMAIL", $payload["email"]);
            $cornerstone = $this->iwahash($cornerstone, "AUTH", "TRUE");
            $cornerstone = $this->iwahash($cornerstone, "ROLES", $roles);
            $cornerstone = $this->iwahash($cornerstone, "CURRENT_ROLE", $user->current_role);


            //Save current token to current session ID - unique session resolving per user
            //based on storing the tokenjo
            $_SESSION['creds'][urlencode($cornerstone)] = ['token' => $cornerstone,
							   'user' => $payload['email']];
	    $_SESSION['user'] = $user;

            return $response->withHeader('planck', $cornerstone)->withJson(array('planck' => $cornerstone,
										 'state' => 'success',
										 'auth' => true));
        }
      }catch(Exception $e){
	print_r($e);
      }
    }

  public function getSignUp($request,$response)
  {
    try{
    //Create cornerstone for hashgraph
    $origin = env('HASH_BASE');

    //Get timestamp for now
    $now = UniversalTimestamp::now();

    //Initialize hashgraph with timestamp and data for frontend
    $cornerstone = $this->iwahash($origin, "TIMESTAMP", $now);
    $cornerstone = $this->iwahash($cornerstone, "ORIGIN", $origin);
    $cornerstone = $this->iwahash($cornerstone, "SESSION_AUTH", "false");

    //TODO: Serving the planck in the header and json response - don't know which will be faster to do for demo
    return $response->withHeader('planck', $cornerstone)
      ->withJson(array('planck'=>$cornerstone,
		       'dehashed'=>$this->dehash($cornerstone)
		       ));
    }catch(Exception $e){
      print_r($e);
    }
  }

  public function getSignUpLanding($request,$response)
  {
    return $this->view->render($response,'app-boot.twig');
  }

  public function postSignUp($request, $response) {
    try{
      $roles = ['recruiter', 'candidate'];

      $validation = $this->validator->validate($request, [
							  'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
							  'password' => v::noWhitespace()->notEmpty(),
							  'role'=>v::notEmpty()->in($roles)
							  ]);

      if ($validation->failed()) {
	$failmsg = 'Validation Failed - Email taken or in invalid format. ';
	$state = 'error';
	return $response->withJson(array('state' => $state, 'message' => $failmsg));
      }

      $role = $request->getParam('role');

      $umail = $request->getParam('email');
      $token = env('TOKEN');

      $uniqueId = 'prizm';
      $uniqueId = $this->iwahash($uniqueId, "UMAIL", $umail);
      $uniqueId = $this->iwahash($uniqueId, "TOKEN", $token);
      $uniqueId = $this->iwahash($uniqueId, "DATE", date('Ymdhis'));

      $aname = $umail;
      $aname = explode('@', $aname)[0] . explode('@', $aname)[1] . md5(date('YMDS'));

      $firstname = !empty($request->getParam('firstname')) ? $request->getParam('firstname') : null;
      $lastname = !empty($request->getParam('lastname')) ? $request->getParam('lastname') : null;

      $passw = $request->getParam('password');
      $group = $request->getParam('chosengroup');

      $user = User::create([
			    'email' => $umail,
			    'name' => $aname,
                'first_name' => $firstname,
                'last_name' => $lastname,
			    'password' => password_hash($passw, PASSWORD_DEFAULT),
			    'activ_code' => urlencode($uniqueId), // <-- add the activation code to database
			    'group_id' => $group,
			    'cvadded' => false,
			    ]);
        $user->current_role = strtolower($role);
      $role = 'is_' . strtolower($role);
      $user->$role = true;
      $user->save();

      $targetEmail = $request->getParam('email');

      $alink = env('FRONTEND_URL') . "auth/confirm?code=" . urlencode($uniqueId);
      $arole = $request->getParam('role');
      $auname= env('MAIL_FROM');

      $mail = new Message;
      $mail->setFrom($auname)
	->addTo($targetEmail)
	->setSubject('Please confirm your email from Refair.me')
	->setHTMLBody(renderEmailTemplate('signup', ['role' => $arole,
						     'link' => $alink]));

      if (!$this->mailer->send($mail)) {
	$message = "Succesfull Registration!";
      } else {
	$errmsg = "Mailer could not send Emails, but user added.";
	throw new \Exception($errmsg);
      }

      return $response->withJson(array('message' => $message, 'state' => 'Success'));
    }catch(\Exception $e){
      print_r($e);
    }
  }

  public function confirmEmail($request,$response)
  {
    try{
      if (!$request->getParam('code')) {
	$errmsg = 'No activation code available!';

	return $response->withJson(['message'=>$errmsg]);
      }

      $user = User::where('activ_code', $request->getParam('code'))->first();

      if($user->activ == 1){
	$this->flash->addMessage('info','This user had already been activated.');
	return $this->response->withRedirect($this->router->pathFor('auth.signin'));
      }else{
	$user->activ = 1;
	$user->save();

	//TODO: locale support necessary
	$message = 'Your signup is a success. You can sign in now! Please return to your application window. ';

	return $this->response->withJson( [ 'message' => $message ]);
      }
    }catch(PDOException $e){
      return print_r($e);
    }
  }

  public function csrftoken($request, $response, $args){
    try{
    $session = new \SlimSession\Helper;

      if(isset($session['csrf_keypair'])){
	return $response->withJson($session['csrf_keypair']);
      }else{

	$slimGuard = new \Slim\Csrf\Guard;
	//    $slimGuard->validateStorage();
	// Generate new tokens
	$csrfNameKey = $slimGuard->getTokenNameKey();
	$csrfValueKey = $slimGuard->getTokenValueKey();
	$keyPair = $slimGuard->generateToken();

	$session['csrf_keypair'] = $keyPair;

	return $response->withJson($keyPair);
      }
    }catch(\Exception $e){
      return print_r($e);
    }
  }

}
