<?php
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class Location_Model extends MY_Model
{
    public $before_create = array( 'timestamps(regdate)');
    
    protected $stateOfDuplication = false;
    
    public $location = array(
            'id'=>NULL,
            'jobref_hashes'=>NULL,
            'name'=>NULL,
            'city'=>NULL,
            'country'=>NULL,
            'address'=>NULL,
            'zip'=>NULL,
            'lat'=>NULL,
            'lon'=>NULL,
            'hash'=>NULL,
            'regdate'=>NULL
         );


    public function insertLocation($location)
    {
        $this->location = $location;
        $this->insert($this->location);
        return true;
    }

}