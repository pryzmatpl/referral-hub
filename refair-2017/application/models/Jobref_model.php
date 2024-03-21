<?php
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class Jobref_Model extends MY_Model
{
    public $before_create = array( 'timestamps(regdate)');
    
    protected $stateOfDuplication = false;
    
    public $jobref = array(
            'id'=>NULL,
            'referred_id'=>NULL,
            'location_id'=>NULL,
            'name'=>NULL,
            'keywords'=>NULL,
            'regdate'=>NULL,
            'hash'=>NULL,
            'state'=>NULL,
            'jobid'=>NULL,
            'referrer_id'=>NULL,
         );


    public function insertJobref($jobrefd)
    {
        $this->jobref = $jobrefd;
        $this->insert($this->jobref);
        return true;
    }
    
    protected function timestamps($jobrefd){
        $jobrefd['regdate']=date('Y-m-d H:i:s');
        return $jobrefd;
    }


}