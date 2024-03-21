<?php
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class Jobdesc_Model extends MY_Model
{
    public $before_create = array( 'timestamps(regdate)', 'hashit(hash)');
    
    protected $stateOfDuplication = false;
    public $_table='jobdescs';
    
    public $jobdesc = array(
            'id'=>NULL,
            'jobtitle'=>NULL,
            'required_exp'=>NULL,
            'required_fund'=>NULL,
            'required_relocation'=>NULL,
            'required_remote'=>NULL,
            'regdate'=>NULL,
            'keywords'=>NULL,
            'location'=>NULL,
            'description'=>NULL,
            'poster_id'=>NULL,
            'bounty'=>NULL,
            'hash'=>NULL
         );


    public function insertJobdesc($jobdescd)
    {
        $this->jobdesc = $jobdescd;
        if($this->isduplicate($this->jobdesc)) return false;
        $this->insert($this->jobdesc);
        return true;
    }

    protected function isduplicate($jobdescd){
        $jobdesca = $this->get_by('id',$jobdesc['id']);

        if( is_object($jobdesca) ){
            if($jobdesca->id != 0){
                return true;
            }else return false;
        }else{
            if($jobdesca['id'] != 0){
                return true;
            }else return false;
        }
    }

    protected function timestamps($jobrefd){
        $jobrefd['regdate']=date('Y-m-d H:i:s');
        return $jobrefd;
    }

    protected function hashit($jobdesc){
        $jobdesc['hash'] = base64_encode($jobdesc['id'].'~'.$jobdesc['jobtitle'].'~'.$jobdesc['regdate'].'~'.$jobdesc['poster_id'].'~'.$jobdesc['bounty']);
        return $jobdesc;
    }
}