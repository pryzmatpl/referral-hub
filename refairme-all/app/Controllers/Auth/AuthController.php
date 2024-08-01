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

  public function postSignIn($request,$response)
  {
    //Attempt authentication
    $payload = $request->getQueryParams();
    
    $auth = $this->auth->attempt( $payload["email"],
				  $payload["password"] );

    if (!$auth) {
      $thistate = "Validation failed - you stated a malformed email address or a wrong password for ".$request->getParam('email');
      
      return $response->withHeader('Access-Control-Allow-Origin','*')
	->withJson(array('message' => $thistate,
			 'state' => 'error',
			 'auth' => false));
      
    }else{
      //Create cornerstone for hashgraph
      $origin = env('HASH_BASE');

      //Get timestamp for now
      $now = UniversalTimestamp::now();

      //Initialize hashgraph with timestamp and data for frontend
      $cornerstone = $this->iwahash($origin, "TIMESTAMP", $now);
      $cornerstone = $this->iwahash($cornerstone, "ORIGIN", $origin);
      $cornerstone = $this->iwahash($cornerstone, "EMAIL", $payload["email"]);
      $cornerstone = $this->iwahash($cornerstone, "AUTH", "TRUE");


      //Save current token to current session ID - unique session resolving per user
      //based on storing the tokenjo
      $_SESSION['creds'][urlencode($cornerstone)] = ['token'=>$cornerstone,
						     'user' => $payload['email'] ];
      
      return $response->withJson(array('planck'=>$cornerstone,
									  'state'=>'success',
									  'auth'=>true));
      
    }
  }
  
  public function getSignUp($request,$response)
  {
    //Create cornerstone for hashgraph
    $origin = env('HASH_BASE');

    //Get timestamp for now
    $now = UniversalTimestamp::now();

    //Initialize hashgraph with timestamp and data for frontend
    $cornerstone = $this->iwahash($origin, "TIMESTAMP", $now);
    $cornerstone = $this->iwahash($cornerstone, "ORIGIN", $origin);
    $cornerstone = $this->iwahash($cornerstone, "SESSION_AUTH", "false");
 
    //TODO: Serving the planck in the header and json response - don't know which will be faster to do for demo      
    return $response->withJson(array('planck'=>$cornerstone,
		       'dehashed'=>$this->dehash($cornerstone)
		       ));
  }
    
  public function getSignUpLanding($request,$response)
  {
    return $this->view->render($response,'app-boot.twig');
  }

  public function postSignUp($request,$response)
  {
    $validation = $this->validator->validate($request,[
						       'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
						       'password' => v::noWhitespace()->notEmpty()
						       ]);

    if ($validation->failed()) {
      $failmsg = 'Validation Failed - Email taken or in invalid format';
      $state = 'error';
      return $response->withJson( array('state'=>$state, 'message'=> $failmsg) );
    }

    $umail = $request->getParam('email');
    $token = env('TOKEN');

    $uniqueId = 'prizm';
    $uniqueId = $this->iwahash($uniqueId, "UMAIL", $umail);
    $uniqueId = $this->iwahash($uniqueId, "TOKEN", $token ); 
    $uniqueId = $this->iwahash($uniqueId, "DATE", date('Ymdhis')); 

    $aname = $umail;
    $aname = explode('@',$aname)[0].explode('@',$aname)[1].md5(date('YMDS'));

    $passw = $request->getParam('password');
    $group = $request->getParam('chosengroup');
	
    $user = User::create([
			  'email' => $umail,
			  'name' => $aname,
			  'password' => password_hash($passw,PASSWORD_DEFAULT),
			  'activ_code' => urlencode($uniqueId), // <-- add the activation code to database
			  'group_id' => $group,
			  'cvadded' => false
			  ]);

    $burl=env('BACKEND_URL');
    $mail = new Message;
    $mail->setFrom('piotr.slupski@pryzmat.pl')
      ->addTo($request->getParam('email'))
      ->setSubject('Please confirm your email from Refair.me')
      ->setHTMLBody("Hello, this email confirms you attempted to register with refair.me.".
		    "<br/> Click this URL: <br /><a href=\"" . $burl . "auth/confirm?code=" . urlencode($uniqueId) ."\">Confirm your account</a><br/>You are registered as ".$request->getParam('chosengroup'));

    if( !$this->mailer->send($mail)){
      $message = "Succesfull Registration!";
    }else{
      $errmsg = "Mailer could not send Emails, but user added.";
      throw new \Exception($errmsg);
    }
	
    return $response->withJson(array('message'=>$message, 'state'=>'Success'));
  }
    
  public function getChangePass($request,$response,$args)
  {
    if( isset($args['hash']) ){
      $ahash = $args['hash']; //TODO: Remeber url encode/decode for HASHES!
      $unwrap = array();
      $data = $this->iwadehash($ahash,$unwrap);
      $amail = $this->getOps(explode('~',$data));
	
      return $this->view->render($response,
				 'auth/password/change.twig',
				 array( 'fromemail'=> 'indeed',
					'aemail' => $amail['EMAIL']) );
    }else{
      return $this->view->render($response,'auth/password/change.twig');
    }
  }

  public function postChangePass($request,$response)
  {
    $validation = $this->validator->validate($request,[
						       'email' => v::noWhitespace()->notEmpty()->email()
						       ]);

    if ($validation->failed()) {
      $this->flash->addMessage('error','Validation failed - you stated a malformed email address.');
      return $response->withRedirect($this->router->pathFor('auth.signup'));
    }

    $activCode = 'prizm';
    $activCode = $this->iwahash($activCode, "DATE:".date('Ymdhis'));
    $activCode = $this->iwahash($activCode, "EMAIL:".$request->getParam('email'));
    $activCode = urlencode($this->iwahash($activCode, "ACTION:ACTIVATION"));
 
    $user = json_decode(User::where('email', $request->getParam('email'))->get(), true)[0];

    if($user['id'] == NULL){
      $this->flash->addMessage('error','Validation failed, you are not registered. Please sign up first.');
      return $response->withRedirect($this->router->pathFor('auth.signup'));
    }

    $editableuser = User::where('email', $request->getParam('email'));
    $editableuser->activ = 0;
    $editableuser->activ_code = $activCode;
        
    $updates = ['activ' => 0,
		'activ_code' => $activCode]; // <-- add the activation code to database
    $editableuser->update($updates);
        
    $burl = env('BASE_URL');

    $mail = new Message;
    $mail->setFrom('piotr.slupski@pryzmat.pl')
      ->addTo($request->getParam('email'))
      ->setSubject('Reset your password for Refair.me')
      ->setHTMLBody("Hello, this email confirms your attempt to change your pasword with refair.me.<br/> Click this URL: <br /><a href=" .
		    $burl . "/auth/recover/".$activCode."> Recover Your Account </a>. <br/><h3>For security reasons, your account had been disabled - continue with your password recovery to use Refair.me.</h3>");
 
    $this->mailer->send($mail);
    $this->flash->addMessage('info','Your recovery details had just been sent to '.$request->getParam('email'));
 
    return $response->withRedirect($this->router->pathFor('auth.signin'));
  }

  public function changePass($request,$response)
  {
    $validation = $this->validator->validate($request,[
						       'email' => v::noWhitespace()->notEmpty()->email(),
						       'password' => v::noWhitespace()->notEmpty()
						       ]);

    if ($validation->failed()) {
      $this->flash->addMessage('error','Validation failed - you stated a malformed email address CHANGEPASS.');
      return $response->withRedirect($this->router->pathFor('auth.signup'));
    }

    $activCode = 'prizm';
    $nupass = $request->getParam('password');
    $activCode = $this->iwahash($activCode, "UPASS:".$nupass);
    $activCode = $this->iwahash($activCode, "UMAIL:".$request->getParam('email'));
    $activCode = urlencode($this->iwahash($activCode, "TIMESTAMP:".date('Y M D H S')));
 
    $editableuser = User::where('email',
				$request->getParam('email'));
    if( !isset($editableuser) ){
      $this->flash->addMessage('error','Validation failed, you are trying to change the email of the user. Please do not. '.$nupass.$request->getParam('email'));
      return $response->withRedirect($this->router->pathFor('auth.signup'));
    }
	
    $editableuser->activ_code = urlencode($activCode);
    $editableuser->activ_code = password_hash($request->getParam('password'),PASSWORD_DEFAULT);
    $updates = ['password' => password_hash($request->getParam('password'),PASSWORD_DEFAULT),
		'activ_code' => $activCode]; // <-- add the activation code to database
    $editableuser->update($updates);

    //Checking if user used linkedin
    $liuser = OA2Clients::where('user_id',
				$request->getParam('email'));
    if( isset($liuser->user_id) ){
      $liuser->client_secret = $activCode;
      $upd=['client_secret'=>$activCode];
      $liuser->update($upd);
    }

    $burl = env('BASE_URL');

    $mail = new Message;
    $mail->setFrom('piotr.slupski@pryzmat.pl')
      ->addTo($request->getParam('email'))
      ->setSubject('Please confirm your new password for Refair.me')
      ->setHTMLBody("Hello, this email confirms your attempt to change your pasword at refair.me.<br/> Click this URL: <br /><a href=" .
		    $burl . "/auth/confirm?code=" . $activCode .">" . $burl . "/auth/confirm?code=" . $activCode ."</a>.<br/>Thank you for using Refair.me!");
 
    $this->mailer->send($mail);
    $this->flash->addMessage('info','Your account\'s password had just been changed. Please check your inbox!');
 
    return $response->withRedirect($this->router->pathFor('auth.signin'));
  }

  public function confirmEmail($request,$response)
  {
    try{
      if (!$request->getParam('code')) {
	$errmsg = 'No activation code available!';
	    
	return $response->withJson(['message'=>$errmsg]);
      }
	
      $user = User::where('activ_code', urlencode($request->getParam('code')));
	
      if($user->activ == 1){
	$this->flash->addMessage('info','This user had already been activated.');
	return $this->response->withRedirect($this->router->pathFor('auth.signin'));
      }else{
	$user->activ = 1;
	$user->update(['activ'=>1]);

	//TODO: locale support necessary
	$message = 'Your signup is a success. You can sign in now! Please return to your application window. ';

	return $this->response->withJson( [ 'message' => $message ]);
      }
    }catch(PDOException $e){
      return print_r($e);
    }
  }
    
  public function csrftoken($request, $response, $args){
    $session = new \SlimSession\Helper;
    try{
      
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