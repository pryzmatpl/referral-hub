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
    public $before_create = array( 'timestamps(regdate)');
    
    protected $stateOfDuplication = false;
    
    public $jobdesc = array(
            'id'=>NULL,
            'jobtitle'=>NULL,
            'description'=>NULL,
            'required_exp'=>NULL,
            'required_fund'=>NULL,
            'required_relocation'=>NULL,
            'required_remote'=>NULL,
            'regdate'=>NULL,
            'location_id'=>NULL,
            'keywords'=>NULL
         );


    public function insertJobdesc($jobdesc)
    {
        $this->jobdesc = $jobdesc;
        $this->insert($this->jobdesc);
        return true;
    }

}