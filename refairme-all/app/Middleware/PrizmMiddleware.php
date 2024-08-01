<?php
namespace App\Middleware;

use Nette\Mail\Message;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Product;
use App\Models\OA2Clients;
use App\Models\UserPermission;
use App\Models\Group;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use Litipk\Jiffy\UniversalTimestamp;
//Adding PSR classes to boost our auth controller

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carlosocarvalho\SimpleInput\Input\Input;

const ORIGIN = 'prizm';
const SEPARATOR = '~';
const CHILDSEPARATOR = ':';

/**
 *
 */
class PrizmMiddleware extends Middleware
{

  public function __invoke($request,$response,$next)
  {
      $aroute = $request->getUri()->getPath();

      //In case we confirm the user, drop this middleware
      if($aroute === '/auth/confirm'){
	$response = $next($request, $response);
	return $response->withJson(['message'=>"Your account is confirmed. Return to your application to log in!",
				    'status'=>"Success"]);
      }

      return $next($request, $response);

  }
  

  public function origin($endpoint){
    return $this->cleanhash($endpoint);
  }

  public function cleandata($endpoint){
    $curr = base64_decode($endpoint);
    $immediate = explode(SEPARATOR, $curr);
    $data = array_slice($immediate,1);
    unset($immediate);
    return join(SEPARATOR,$data);
  }

  public function traverse($unwrapped, $optid){
    foreach($unwrapped as $value){
      $ops = explode(CHILDSEPARATOR,$value);
      $actualOp = strval($ops[0]);
      $data = strval($ops[1]);
      if( !strcmp(strval($optid), $actualOp) ) return $data;
    }

    return 'prizm';
  }

  public function cleanhash($endpoint){
    if( !strcmp($endpoint,'') ){
      throw new \Exception("Endpoint cannot be empty to retrieve next hash");
    }

    $elems = base64_decode($endpoint);

    $exploded_elems = explode(SEPARATOR, $elems);

    return $exploded_elems[0];
  }
  
  public function iwadehash($endpoint,$data=''){
    if( !strcmp($endpoint, ORIGIN) ){
      return $data;
    }

    $nexthash = $this->cleanhash($endpoint);
    $data = $this->cleandata($endpoint);

    if( !strcmp( $data, $this->iwadehash($nexthash, $data)) ){
      return $data; //We arrived at single data, should be hash cornerstone
    }else{
      return $data.SEPARATOR.$this->iwadehash($nexthash, $data);
    }
  }

  public function dehash($endpoint){
    $dehashedStr = $this->iwadehash($endpoint);
    $dehashedToks = explode(SEPARATOR, $dehashedStr);
    
    foreach($dehashedToks as $val){
      $rawd = explode(CHILDSEPARATOR, $val);

      if(count($rawd) > 2){
	throw \Exception('Something went wrong when separating token '.print_r($rawd));
      }
      
      $key = $rawd[0];
      $val = $rawd[1];
      
      $data[$key] = urldecode($val);
    }
    
    return $data;
  }
  
  public function iwahash($origin,$dataid,$data){
    //Initialize origin
    if( !strcmp($origin,'') ){
      throw new \Exception("Origin is required, it is currently: ' ".$origin." '.");
    }

    //Check if dataid has the childseparator within it
    if( strpos($dataid, CHILDSEPARATOR) != FALSE ){
      throw new \Exception("dataid cannot have the child separator, ' ".CHILDSEPARATOR." ' , within it");
    }

    //Check if dataid is not empty
    if( !strcmp($dataid,'') ){
      return $origin;
      throw new \Exception('Origin must always be set for the hashgraph');
      // Origin should be hashed always
    }

    //Return the hashgraph
    return base64_encode($origin.SEPARATOR.$dataid.CHILDSEPARATOR.urlencode($data));
  }

  public function spawn($queryHash){       
    $placeholder = $this->iwadehash($queryHash);
    $placearr = explode(SEPARATOR,$placeholder);

    foreach($placearr as $item){
      $command = explode(CHILDSEPARATOR,$item);
      $retarr[] = [$command[0]=>$command[1]];
    }
    
    return json_encode($retarr,true);
  }
}