<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Define the structure of the layout
define('WAITING', "waiting");
define('FLASH', "flash");
define('QUOTES',"quotes");
define('LOGIN',"login");
define('REFAIR',"refair");
define('REFERRALS',"refair-referrals");

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

require('Auth.php');

class Account extends Auth {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *    http://example.com/index.php/welcome
     *  - or -
     *    http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct(){
        parent::__construct();
		$this->load->database();

        $CI = & get_instance();
        $CI->layout="prism-material";
    }
    
    public function index(){}
    
    public function secure($hhash,$site='')
    {
        if(!strcmp($site,'')){
            $hash=base64_decode($hhash);
            $secarr=explode('~',$hash);
            $email=$secarr[0];
            $usuario = $this->user_model->get_by('email',$secarr[0]);
            $this->emailes=$email;

            
            $CI = & get_instance();
            $CI->active = password_verify($secarr[2],$secarr[1]);
            $CI->layout="auth";
            $this->hhhash = $hhash;
            
            $this->styles = array("style");
            $this->menus = array("Refair/secure/".$hhash."~Refair",
                                 "Account/secure/".$hhash."~Dashboard",
                                 "Refair/secure/".$hhash."/jobs~All Jobs",
                                 "Account/secure/".$hhash."/profile~Your Profile",
                                 "Refair/secure/".$hhash."/referrals~Your Referrals",
                                 "Refair/secure/".$hhash."/describeimports/".$hhash."~Your LinkedIn imports",
                                 "Refair/secure/".$hhash."/referrals~Your Referrals",
                                 "Auth/logout~Log out");
            $this->lowermenus = $this->menus;

            $this->dataJobs = $this->jobdesc_model->get_many_by('poster_id',$email);
            $this->dataLocations = $this->location_model->get_many_by('userid',$email);
            $this->dataReferrals = $this->jobref_model->get_many_by('referrer_id',$email);
            $this->dataReferralsReferred = $this->jobref_model->get_many_by('referred_id',$email);
            $this->dataReferralsReferrer = $this->jobref_model->get_many_by('referrer_id',$email); //Referred BY other ppl

            $this->dataApplications = array();
            foreach($this->dataReferralsReferrer as $value){
                if(!strcmp($value->referred_id,$value->referrer_id)) $this->dataApplications[]=$value;
            }
        
            if($this->ion_auth->logged_in()){            
                $this->session->set_flashdata('message', array("Hello <a id=\"emailuid\" name=\"emailuid\"></a>!. You agree to cookies."));
            }

            $this->userAble = true; //we are in the accounts dashboard we see all
            
            $this->pasdat = array($this->dataApplications,
                                  $this->dataReferralsReferred,
                                  $this->dataReferralsReferrer,
                                  $this->userAble,
                                  $this->menus,
                                  $this->emailes,
                                  $this->dataJobs,
                                  $this->hhhash,
                                  $this->dataReferrals,
                                  $this->dataLocations);
            
            $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
            $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
            $this->parts[REFAIRHORIZON] = $this->load->view('horizon/refair-horizon',$this->pasdat,true);
            $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
            $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
            $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
            $this->parts[CONTENTE] = $this->load->view('refair-user',$this->pasdat,true);
            $this->parts[REFAIRJOBS] = $this->load->view('refair-userjobs',$this->pasdat,true);
            $this->parts[REFERRALS] = $this->load->view('refair-referrals',$this->pasdat,true);
            $this->parts[LOCATIONS] = $this->load->view('refair-locations',$this->pasdat,true);
            $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
            $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
            $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
            $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true); 
            $this->parts[MODALADDLOCATION] = $this->load->view('modal-addlocation',$this->pasdat,true);
            $this->parts[MODALDELETEREF] = $this->load->view('modal-deleteref',$this->pasdat,true);
            $this->parts[MODALDELETELOCATION] = $this->load->view('modal-deletelocation',$this->pasdat,true);
            $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
            $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
            $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
            $this->parts[FLASH] = $this->load->view('flash',$this->pasdat,true);
        }else{
            $CI = & get_instance();
            switch($site){
            case 'network': {$this->network($hhash); break;}
            case 'profile': {$this->profile($hhash); break;}
            default: $this->index();
            }
        }
    }

    private function profile($hhash){
        echo "<h2>Profile</h2>";
        $hash=base64_decode($hhash);
        $secarr=explode('~',$hash);
        $this->emailes=$secarr[0];
        
        $usuario = $this->user_model->get_by('email',$secarr[0]);
        
        $CI = & get_instance();
        $CI->active = password_verify($secarr[2],$secarr[1]);
        $CI->layout="auth";
        
        $this->styles = array("style");
        $this->menus = array("Refair/secure/".$hhash."~Refair",
                             "Account/secure/".$hhash."~Dashboard",
                             "Refair/secure/".$hhash."/jobs~All Jobs",
                             "Account/secure/".$hhash."/profile~Your Profile",
                             "Refair/secure/".$hhash."/referrals~Your Referrals",
                             "Auth/logout~Log out");
        
        $dataJobss = $this->jobdesc_model->get_many_by("poster_id",$usuario->email);
        
        $this->dataJobs = $dataJobss;
        $this->pasdat = array($this->menus,$this->emailes,$this->dataJobs);
        
        $this->session->set_flashdata('message', array("Your control pod for Prism, dear <a id=\"emailuid\" name=\"emailuid\">".$this->emailes."</a>. You agree to cookies."));
        
        $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[CONTENTE] = $this->load->view('refair-profile',$this->pasdat,true);
        $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
        $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
        $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[FLASH] = $this->load->view('flash',$this->pasdat,true);
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
