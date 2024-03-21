<?php
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class Linkedin_import_Model extends MY_Model
{
    public $before_create = array( 'timestamps(regdate)');
    
    protected $stateOfDuplication = false;
    
    public $linkedin_import = array(
            'id'=>NULL,
            'firstName'=>NULL,
            'lastName'=>NULL,
            'company'=>NULL,
            'title'=>NULL,
            'email'=>NULL,
            'phone'=>NULL,
            'notes'=>NULL,
            'tags'=>NULL,
            'uidInviter'=>NULL,
            'regdate'=>NULL
         );


    public function insertLinkedin_import($linked)
    {
        $this->linkedin_import = $linked;
        $this->insert($this->$linkedin_import);
        return true;
    }
    
    protected function timestamps($linked){
        $linked['regdate']=date('Y-m-d H:i:s');
        return $linked;
    }

    public function fetch_data($limit, $id=1, $uid) {
        $query = $this->db->query("SELECT * from linkedin_imports where uidInviter = \"".$uid."\" limit ".$id.",".$limit);
        
        $data=array();
        foreach ($query->result() as $row){
                $data[] = $row;
         }
        return $data;
    }
}