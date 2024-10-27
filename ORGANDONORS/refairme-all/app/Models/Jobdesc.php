<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobdesc extends Model
{
    public $before_create = array( 'timestamps(regdate)' , 'hashit(hash)' );
    protected $table = 'jobs';
    
    protected $stateOfDuplication = false;
    
    public $id;
    public $title;
    public $exp;
    public $fund;
    public $relocation;
    public $remote;
    public $regdate;
    public $keywords;
    public $location;
    public $travelPercentage;
    public $remotePercentage;
    public $relocationPackage;
    public $projectId;
    public $other;
    public $description;
    public $posterId;
    public $companyId;
    public $contractType;
    public $currency;
    public $bounty;
    public $hash;

    protected $fillable = [
            'id',
            'title',
            'exp',
            'fund',
            'relocation',
            'remote',
            'regdate',
            'keywords',
            'location',
            'travelPercentage',
            'remotePercentage',
            'relocationPackage',
            'projectId',
            'companyId',
            'other',
	    'contractType',
	    'currency',
            'description',
            'posterId',
            'bounty',
            'hash'
    ];


    public function insertLocation($locatione)
    {
        $this->location = $locatione;
        $this->insert($this->location);
        return true;
    }

    protected function timestamps($jobrefd){
        $jobrefd['regdate']=date('Y-m-d H:i:s');
        return $jobrefd;
    }

    protected function hashit($jobrefd){
        $jobrefd['hash'] = base64_encode($jobrefd['userid'].'~'.$jobrefd['regdate'].'~'.$jobrefd['name'].'~'.$jobrefd['address']);
        return $jobrefd;
    }

   public static function printArrays($jobdesc){
     $ret = $jobdesc;
     $ret['keywords'] = explode(",",$jobdesc['keywords']);
     $ret['fund'] = json_decode($jobdesc['fund'],true);
     $ret['exp'] = json_decode($jobdesc['exp'],true);
     return $ret;
    }
    
    public static function nuAll(){
      $allstuff = Jobdesc::all();

      foreach($allstuff as $jobdesc){
	$ret[]=Jobdesc::printArrays($jobdesc);
      }

      return $ret;
    }

    public static function getPretty($jobdesc){
      $var = Jobdesc::printArrays($jobdesc)[0];
      return $var;
    }
    
}



