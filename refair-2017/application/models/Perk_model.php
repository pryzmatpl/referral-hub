<?php
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class Perk_Model extends MY_Model
{
    public $before_create = array( 'timestamps(regdate)', 'hashit(hash)');
    
    protected $stateOfDuplication = false;
    public $_table='perks';
    
    public $perk = array(
            'id'=>NULL,
            'jobid'=>NULL,
            'name'=>NULL,
            'uid'=>NULL,
            'agreed_employer'=>NULL,
            'agreed_employee'=>NULL,
            'hash'=>NULL,
            'regdate'=>NULL,
            'agreed_referee'=>NULL
         );

    public function insertPerk($perks)
    {
        $this->$perk = $perks;
        if($this->isduplicate($this->perk)) return false;
        $this->insert($this->perk);
        return true;
    }

    protected function isduplicate($jobdescd){
        $jobdesca = $this->get_by('id',$jobdescd['id']);

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
        $jobdesc['hash'] = base64_encode($jobdesc['id'].'~'.$jobdesc['name'].'~'.$jobdesc['regdate'].'~'.$jobdesc['uid'].'~'.$jobdesc['jobid']);
        return $jobdesc;
    }
}