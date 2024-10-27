<?php
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class File_Model extends MY_Model
{
    public $before_create = array( 'timestamps(regdate)');
    
    protected $stateOfDuplication = false;
    public $_table='files';
    
    public $file = array(
            'id'=>NULL,
            'filename'=>NULL,
            'title'=>NULL,
            'hash'=>NULL,
            'regdate'=>NULL
         );


    public function insertFile($jobdescd)
    {
        $this->$file = $jobdescd;
        if($this->isduplicate($this->$file)) return false;
        $this->insert($this->$file);
        return true;
    }

    protected function isduplicate($filein){
        $filea =$this->get_by('hash',$filein['hash']);

        if( is_object($filea) ){
            if($filea->id != 0){
                return true;
            }else return false;
        }else{
            if($filea['id'] != 0){
                return true;
            }else return false;
        }
    }

    protected function timestamps($jobrefd){
        $jobrefd['regdate']=date('Y-m-d H:i:s');
        return $jobrefd;
    }

}