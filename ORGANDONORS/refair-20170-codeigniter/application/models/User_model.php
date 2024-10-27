<?php
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class User_Model extends MY_Model
{
    public $before_create = array( 'timestamps(user)','token(user)');

    protected $stateOfDuplication = false;
    
    public $user = array(
            'id'=>NULL,
            'email'=>NULL,
            'regdate'=>NULL,
            'active'=>false,
            'token'=>NULL
         );

    protected function isduplicate($user){
        $usear = $this->get_by('email',$user['email']);

        if( is_object($usear) ){
            if($usear->id != 0){
                return true;
            }else return false;
        }else{
            if($usear['id'] != 0){
                return true;
            }else return false;
        }
    }
    
    public function insertUser($userr)
    {
        $this->user['email']=$userr['email'];
        $this->stateOfDuplication = $this->isduplicate($this->user);
        
        if($this->stateOfDuplication) return false;
        $this->insert($this->user);
        return true;
    }
    
    public function validateUser($email,$token){
        $this->user = $this->user_model->where('email',$email);
        if($user['token'] == $token){
            $this->user_model->update('active',true);
            return $user;
        }
        return false;
    }
    
    protected function isvalid($email,$token)
    {
        $this->user = $this->user_model->where('email',$email);
        if( $this->user['token'] == $token){
            return true;
        }
        return false;
    }    
    
    protected function timestamps($user){
        $user['regdate']=date('Y-m-d H:i:s');
        return $user;
    }

    protected function token($user)
    {
        $token = substr(sha1(rand()), 0, 30) +"~`"+ $user['regdate']  +"~`"; //Change the strings into integers raw? 
        $user['token']=$token;
        return $user;
    }

    public function verify($identity,$password,$remember=false){
        $userhash = $this->ion_auth->login($identity,$password,$remember);
        return $userhash;
    }
}