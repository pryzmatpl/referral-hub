<?php
namespace App\Commands;

use App\Models\Company;
use App\Models\Jobdesc;
use App\Models\User;
use Exception;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;


class ProfileSendCommand {

  public function command($args) {
    try{
      $resources_dir = __DIR__ . '/../../resources';
      $filename = '/data/mail2_test.json';
      $users = json_decode(file_get_contents($resources_dir.'/'.$filename), true);

      $settings_mailer = [
			  'host' => getenv('MAIL_HOST'),
			  'username' => getenv('MAIL_USERNAME'),
			  'password' => getenv('MAIL_PASSWORD'),
			  'port'=> getenv('MAIL_PORT'),
			  'secure' => getenv('MAIL_SECURE')
			  ];
	
      if (strpos(getenv('MAIL_HOST'), '@gmail.com') != -1) { // For gmail / tls to work.
	$settings_mailer['context'] = [
				       'ssl' => [
						 'verify_peer' => false,
						 'verify_peer_name' => false,
						 'allow_self_signed' => true
						 ]
				       ];
      }

      $mailer = new SmtpMailer($settings_mailer);
	
      foreach ($users as $user) {
	$categories = array_keys($user['matched_jobs']);
	$matched_jobs = &$user['matched_jobs'];
	$html = renderEmailTemplate('new_json_email', ['categories' => $categories, 'matched_jobs' => $matched_jobs]);
	//exit($html);

	$email = new Message();
	$email->setFrom(env('MAIL_USERNAME'))
	  ->addTo($user['email'])
	  ->addBcc(env('OVERWATCH'))
	  ->setSubject($user['first_name'].', do you need to move company to get a promotion?')
	  ->setHTMLBody($html);

	print_r("Sent email to " . $user['email'] ."\n");

	if ($mailer->send($email)) {
	  throw new Exception('Email could not be sent.');
	}

      }
    }catch(Exception $e){
      print_r($e);
    }
  }
}

  /*<style type="text/css">
    table { border-collapse: collapse; color: #787878; font-size: 20px; }
    td { width: 386px; padding: 30px; }
    .grey { background-color: #f2f2f2; }
    .big { font-size: 26px; text-align: center; }
    .blueish { color: #338ba5; font-weight: bold; }
    a { text-decoration: none; }
    </style>*/