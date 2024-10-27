<?php
namespace App\Controllers;

use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;
use App\Models\User;
use App\Models\Cart;
use App\Classes\Fitnesscalc;
use App\Classes\Individual;
use App\Classes\Population;
use App\Classes\Algorithm;
use Illuminate\Database\Capsule\Manager as DB;
use \SlimSession\Helper as Session;

define('MODALREFER',"modal-refer");
define('MODALADDLOCATION',"modal-addlocation");
define('MODALDELETELOCATION',"modal-deletelocation");
define('MODALDELETEREF',"modal-deleteref");
define('MODALDELETE',"modal-delete");
define('MODALAPPLY',"modal-apply");
define('REFAIRCONTENT',"refair-content");
define('REFAIRLOCATION',"refair-location");
define('LOCATIONS',"refair-locations");
define('REFAIRPERK',"refair-describeperk");
define('REFAIRHORIZON',"refair-horizon");
define('REFAIRJOBS',"refair-userjobs");

class Account extends Controller {

    public function index(){
        //TODO: Fill this
    }

    private function profile($hhash){
        //TODO: Fill this
        
    }

    
    public function addlocation(){
        try{
        $locationdata = array(
            'id'=>base64_decode($this->input->get('loc_locid')),
            'jobref_hashes'=>NULL, //calculated in model
            'name'=>base64_decode($this->input->get('loc_name')),
            'city'=>base64_decode($this->input->get('loc_city')),
            'country'=>base64_decode($this->input->get('loc_country')),
            'address'=>base64_decode($this->input->get('loc_address')),
            'zip'=>base64_decode($this->input->get('loc_zip')),
            'lat'=>base64_decode($this->input->get('loc_lat')),
            'lng'=>base64_decode($this->input->get('loc_lon')),
            'hash'=>base64_decode($this->input->get('loc_hash')), //calculated in model
            'regdate'=>NULL, //calcluated in model
            'userid'=>base64_decode($this->input->get('loc_userid')),
            'description'=>base64_decode($this->input->get('loc_desc'))
        );

        if(!strcmp($locationdata['id'],"hawking")){
            // there is emptiness in the data
            $locationdata['id']= NULL;
            $locationdata['hash']= NULL;
            if($this->location_model->insert($locationdata)){
                $this->session->set_flashdata('message',"  You have added a new location:".$_GET['loc_name'].$_GET['loc_userid']);
                echo json_encode(array('message',"You have sucessfully added a new location:".$_GET['loc_name']));
            }else{
                echo json_encode(array('message',"You have failed adding a location:".$_GET['loc_name']));
            }
        }else{
            $locid = $locationdata['id'];
            unset($locationdata['id']);
            unset($locationdata['jobref_hashes']);
            unset($locationdata['hash']);
            unset($locationdata['userid']);
            unset($locationdata['regdate']);
            
            if(!$this->location_model->delete($locid)){
                echo json_encode(array('message',"Something fishy is going on - no location:".$_GET['loc_name']));
            }
            
            if($this->location_model->update($locationdata,$locid)){
                $this->session->set_flashdata('message',"  You have updated location:".$_GET['loc_name'].$_GET['loc_userid']);
                echo json_encode(array('message',"You have sucessfully edited a new location:".$_GET['loc_name']));
            }else{
                echo json_encode(array('message',"You have failed editing a  location:".$_GET['loc_name']));
            }
        }
        }catch(Exception $e){
            echo json_encode(array('message',"You met an exception:".$e->value));
        }
    }
    
    private function network($hhash){
        echo "<h2>Network</h2>";
        $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[REFAIRJOBS] = $this->load->view('refair-userjobs',$this->pasdat,true);
        $this->parts[REFAIRCONTENT] = $this->load->view('refair-content',$this->pasdat,true);
        $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
        $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[FLASH] = $this->load->view('flash',$this->pasdat,true);
    }
    
    function _enableRequests(){
        require(APPPATH.'/libraries/Utility/FilteredIterator.php');
        require(APPPATH.'/libraries/Utility/CaseInsensitiveDictionary.php');
        require(APPPATH.'/libraries/Response.php');
        require(APPPATH.'/libraries/Response/Headers.php');
        require(APPPATH.'/libraries/Exception.php');
        require(APPPATH.'/libraries/Exception/HTTP.php');
        require(APPPATH.'/libraries/Exception/Transport.php');
        require(APPPATH.'/libraries/Transport.php');
        require(APPPATH.'/libraries/Transport/cURL.php');
        require(APPPATH.'/libraries/IDNAEncoder.php');
        require(APPPATH.'/libraries/IRI.php');
        require(APPPATH.'/libraries/Cookie/Jar.php');
        require(APPPATH.'/libraries/Cookie.php');
        require(APPPATH.'/libraries/Hooker.php');
        require(APPPATH.'/libraries/Hooks.php');
        require(APPPATH.'/libraries/Requests.php');
    }

    public function deleteloc(){
        //Job id will be hidden in the form
        try{
            if(!strcmp($_GET['lochash'],'NULL')){}else{
                $this->hashloc = base64_decode( $_GET['lochash'] );
                $this->locid = base64_decode( $_GET['locid'] );
                $this->test_del = $this->location_model->get_by('id', $this->locid);

                if(is_object($this->test_del)){
                    if($this->location_model->delete($this->test_del->id)){
                        $jobdescs = $this->jobdesc_model->get_many_by('location',$this->hashloc); //Get jobdescs by location hash
                        foreach($jobdescs as $jobdescrip){
                            echo json_encode(array('message'),$jobdescrip);
                        }
                        $this->session->set_flashdata('message',"You have deleted your location succesfully");
                        echo json_encode(array('message'=>"Sucessfull deleting of location ".$this->hashloc."<br/>"));
                    }else{
                        $this->session->set_flashdata('message',"You have not deleted your location. Something went wrong: ".$this->locid);
                        echo json_encode(array('message'=>"Something went wrong when deleting the location"));
                    }
                }else{
                    $this->session->set_flashdata('message',"That location does not exist in the DB: ");
                    echo json_encode(array('message'=>"Something went wrong when deleting the location"));
                }
            }
        }catch(Exception $e){
            $this->session->set_flashdata('error',"Something went wrong while deleting the location");
            echo json_encode(array('message'=>"Something went wrong deleting the location"));
        }
    }

    private function _login($email,$password){
        //THIS IS A FUCKING TRAVESTY
        //Nothing is base64 here
        $CI=& get_instance();
        $GLOBALS['active']=false;
        
        $CI->password = $this->input->post('password');
        
        $user_row = $this->user_model->get_by('email',$email);
        $passwd_row = $this->password_model->get_by('uid',$user_row->id);
        
        if($passwd_row != false){
            if(!strcmp($CI->password,$passwd_row->secpasswd)){
                if(!strcmp($email,$user_row->email)){
                    $GLOBALS['active']=true;
                    return true;
                }
            }
        }

        return false;
    }
    
    public function notifications(){
    }

    public function notificationslist(){
    }
}
