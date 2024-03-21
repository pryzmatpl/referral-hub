<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Slim\App;
use \Slim\Http\RequestBody;
use \Slim\Http\Request;
use \Slim\Http\Uri;
use \Slim\Http\Headers;
use \Slim\Http\Environment;
use \Slim\Http\Response;
use \App\Controllers\AppointmentsController;

final class AppointmentsControllerTest extends \PHPUnit\Framework\TestCase
{
  public function testCreate()
  {
    $env = \Slim\Http\Environment::mock([
					 'REQUEST_METHOD' => 'POST',
					 'REQUEST_URI' => '/appointments/add',
					 'QUERY_STRING'=>'']
					);


    $response = new Response();
    
    $createParams = ['candidate_id' => 41 ,
		     'recruiter_id' => 42 ,
		     'appointment' => ['from' => 'September 4th 2018, 10:50:19 am',
				       'to' => 'September 4th 2018, 11:50:19 am'],
		     'status' => ['status'=>'FIRST_CONTACT']
		     ];

    $body = new RequestBody();
    $body->write(json_encode($createParams, 1));
    $uri = Uri::createFromEnvironment($env);
    $headers = Headers::createFromEnvironment($env);
    $cookies = [];
    $serverParams = $env->all();

    $request = new Request('POST', $uri, $headers, $cookies, $serverParams, $body);
    
    $resp = AppointmentsController::create($request,$response,[]);

    $this->assertArrayHasKey('id',$resp);
  }

  //Testing the hashing scheme for iwahash with base64_encode
  public function testGet()
  {
    // We need a request and response object to invoke the action
    $environment = \Slim\Http\Environment::mock([
						 'REQUEST_METHOD' => 'GET',
						 'REQUEST_URI' => '/appointments/get',
						 'QUERY_STRING'=>'']
						);
    
    $request = \Slim\Http\Request::createFromEnvironment($environment);
    $response = new \Slim\Http\Response();
    
    // run the controller action and test it
    $response = AppointmentsController::get($request, $response, []);
    
    $this->assertEquals((string)$response->getBody(), '[]');
    
  }

  public function testUpdate()
  {
  }
  public function testDelete()
  {
  }
      
  
}
