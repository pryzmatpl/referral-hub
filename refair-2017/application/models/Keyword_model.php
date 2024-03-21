<?php
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class Keyword_Model extends MY_Model
{
    public $before_create = array( 'timestamps(regdate)');
    
    protected $stateOfDuplication = false;
    
    public $keyword = array(
            'id'=>NULL,
            'uid'=>NULL,
            'termid'=>NULL,
            'keyone'=>NULL,
            'keytwo'=>NULL,
            'keythree'=>NULL,
            'searchterm'=>NULL,
            'regdate'=>NULL,
            'cnt'=>NULL
         );


    public function insertKeyword($keywordd)
    {
        $this->keyword = $keywordd;
        $this->insert($this->keyword);
        return $this->keyword;
    }
    
    protected function timestamps($keywordd){
        $keywordd['regdate']=date('Y-m-d H:i:s');
        return $keywordd;
    }
    
}