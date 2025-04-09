<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $before_create = array( 'timestamps(regdate)' , 'hashit(hash)' );
	protected $table = 'locations';
    
    protected $stateOfDuplication = false;
    
    public $id;
    public $jobref_hashes;
    public $name;
    public $city;
    public $country;
    public $address;
    public $zip;
    public $lat;
    public $lon;
    public $hash;
    public $regdate;
    public $userid;
    public $description;
    public $currency;

    protected $fillable = [
        'id',
        'jobref_hashes',
        'name',
        'city',
        'country',
        'address',
        'zip',
        'lat',
        'lon',
        'hash',
        'regdate',
        'userid',
	'currency',
        'description'
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

    
}



