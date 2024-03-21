<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Exception;
use Nette\Mail\Message;
use Respect\Validation\Validator as v;


class PasswordController extends Controller
{
  public function getChangePassword($request,$response)
  {
    return $this->view->render($response,'auth/password/change.twig');
  }

  public function postChangePassword($request,$response)
  {
    $validation = $this->validator->validate($request,[
						       'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
						       'password' => v::noWhitespace()->notEmpty(),
						       ]);

    if ($validation->failed()) {
      return $response->withRedirect($this->router->pathFor('auth.password.change'));
    }

    $pass = $request->getParam('password');


    $this->auth->user()->setPassword($pass);
    print_r($this->auth->user());

    $this->flash->addMessage('info','Your password was changed');

    return $response->withRedirect($this->router->pathFor('home'));

  }


  public function recoverLink($request, $response)
  {
    $validation = $this->validator->validate($request, [
							'email' => v::noWhitespace()->notEmpty(),
							'link_back' => v::noWhitespace()->notEmpty()
							]);

    if ($validation->failed()) {
      throw new Exception('Validation failed.');
    }

    $user = User::where('email', $request->getParam('email'))->first();

    if (is_null($user)) {
      return $response->withJson(['message' => 'Recovery email sent.', 'state' => 'Success']);
    }

    $user->password_recovery_hash = urlencode(base64_encode($user->id . $user['email'] . uniqid()));

    $alink = $request->getParam('link_back')."?password_recovery_hash=".$user->password_recovery_hash;
    $auname= env('MAIL_FROM');

    $targetEmail = $user->email;
	
    $email = new Message;
    $email->setFrom($auname)
      ->addTo($targetEmail)
      ->setSubject('Password Recovery from Refair.me')
      ->setHTMLBody(renderEmailTemplate('recover', ['link' => $alink,
						    'email'=> $targetEmail]));

    if (!$this->mailer->send($email)) {
      $user->save();
      return $response->withJson(['message' => 'Recovery email sent.', 'state' => 'Success']);
    } else {
      throw new Exception('Email could not be sent.');
    }
  }

    public function recover($request, $response) {
        try {
            $validation = $this->validator->validate($request, [
                'password_recovery_hash' => v::noWhitespace()->notEmpty(),
                'new_password' => v::noWhitespace()->notEmpty(),
            ]);


            if ($validation->failed() || is_null($request->getParam('password_recovery_hash'))) {
                throw new Exception('Validation failed.');
            }

            $user = User::where(['password_recovery_hash' => urlencode($request->getParam('password_recovery_hash'))])->first();

            if (is_null($user)) {
                throw new Exception('User not found.');
            }

            $user->password_recovery_hash = null;
            $user->setPassword($request->getParam('new_password'));
            $user->save();

            return $response->withJson(['message' => 'New password set.', 'state' => 'Success']);
        } catch (Exception $e) {
            print_r($e);
        }
    }
  
}