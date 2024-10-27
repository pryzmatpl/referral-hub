<?php
namespace App\Controllers;

use Exception;
use SlimSession\Helper as Session;
use Illuminate\Database\Capsule\Manager as DB;
use function Exception;

const ORIGIN = 'prizm';
const SEPARATOR = '~';
const CHILDSEPARATOR = ':';

class Controller
{
    protected $container;

    public function transformWeight($weightsOfDb){
        $retdata = [$weightsOfDb['aone']];
        $retdata[] = $weightsOfDb['atwo'];
        $retdata[] = $weightsOfDb['athree'];
        $retdata[] = $weightsOfDb['afour'];
        $retdata[] = $weightsOfDb['afive'];
        $retdata[] = $weightsOfDb['asix'];
        $retdata[] = $weightsOfDb['aseven'];
        $retdata[] = $weightsOfDb['aeight'];
        $retdata[] = $weightsOfDb['anine'];
        $retdata[] = $weightsOfDb['aten'];
        $retdata[] = $weightsOfDb['aeleven'];
        return $retdata;
    }

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};

        }
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
            throw new Exception("Endpoint cannot be empty to retrieve next hash");
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
                throw Exception('Something went wrong when separating token '.print_r($rawd));
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
            throw new Exception("Origin is required, it is currently: ' ".$origin." '.");
        }

        //Check if dataid has the childseparator within it
        if( strpos($dataid, CHILDSEPARATOR) != FALSE ){
            throw new Exception("dataid cannot have the child separator, ' ".CHILDSEPARATOR." ' , within it");
        }

        //Check if dataid is not empty
        if( !strcmp($dataid,'') ){
            return $origin;
            throw new Exception('Origin must always be set for the hashgraph');
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