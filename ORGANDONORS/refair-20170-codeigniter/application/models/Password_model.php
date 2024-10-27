<?php
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class Password_Model extends MY_Model
{
    public $before_create = array( 'timestamps(password)' );
    
    public $password = array(
        'id'=>NULL,
        'uid'=>NULL,
        'secpasswd'=>NULL,
        'regdate'=>NULL,
    );

    protected $isDuplicate = false;

    public function insertPassword($password){
        //TODO: perform operations to keep password securely
        $pass = $this->get_by('uid',$password['uid']);

        if( is_object($pass) ){
            return false; //If is object then is in database
        }else{
            $this->password['secpasswd']=$password['secpasswd'];
            $this->password['uid']=$password['uid'];
            $this->password_model->insert($this->password);
        }
    }

    public function verify($uid,$hash){
        $userhash = $this->get($uid);
        if(password_verify($userhash->password,$hash)){
            return true;
        }
        return false;
    }
    
    protected function timestamps($password){
        $password['regdate']=date('Y-m-d H:i:s');
        return $password;
    }
}