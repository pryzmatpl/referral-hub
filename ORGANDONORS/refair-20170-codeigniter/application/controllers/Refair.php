<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Define the structure of the layout
define('SUBMIT-OPEN', "submit-open");
define('WAITING', "waiting");
define('FLASH', "flash");
define('QUOTES',"quotes");
define('LOGIN',"login");

define('REFAIR',"refair");

define('MODALAPPLY',"modal-apply");
define('MODALDELETE',"modal-delete");
define('MAPPLUGINREF',"mapplugin");
define('MODALDELPERK',"modal-deleteperk");
define('MODALREFER',"modal-refer");
define('MODALDELETEREF',"modal-deleteref");
define('MODALADDLOCATION',"modal-addlocation");
define('MODALDELETELOCATION',"modal-deletelocation");

define('REFAIRJOBS',"refair-userjobs");
define('REFAIRLOCATION',"refair-location");
define('REFAIRPERK',"refair-describeperk");
define('REFAIRTRACKREF',"refair-trackreferral");
define('REFAIRCONTENT',"refair-content");
define('REFAIRHORIZON',"refair-horizon");

define('REFERRALS',"refair-referrals");
define('LOCATIONS',"refair-locations");


require('Auth.php');

class Refair extends Auth {
    
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
    private $active=false;
    
      public function __construct(){
          parent::__construct();
          $CI = & get_instance();
          $this->load->database();
          $CI->layout="auth";
          
          if( $this->ion_auth->logged_in() ){
              redirect("Auth/login",'refresh');
          }
      }
    
    private function _rd_referrer_redirect($referrer=''){
        if(!strcmp($referrer,'')){
            redirect('/Refair/secure/'.
                     base64_encode($this->input->post('identity').
                                   "~".
                                   password_hash($this->input->post('password'),PASSWORD_BCRYPT).
                                   "~".
                                   $this->input->post('password')));
        }else{
            redirect('/Refair/secure/'.
                     base64_encode($this->input->post('identity').
                                   "~".
                                   password_hash($this->input->post('password'),PASSWORD_BCRYPT).
                                   "~".
                                   $this->input->post('password')).'/'.$referrer);
        }
    }
    
    public function index()
    {
        if($this->ion_auth->logged_in()){
            $this->_rd_referrer_redirect();
        }
        
        $this->styles = array("style");
        $this->menus = array("Auth/login~Login");
        $this->lowermenus = array("#about~About us",
                                  "#contact~Contact us",
                                  "Auth/login~Login");
        
        $dataJobs = $this->jobdesc_model->get_all();
        
        $this->pasdat = array($this->menus,$this->lowermenus,$dataJobs);
        //TODO:Load data for popular searches
        //TODO:Load data for popular users
        //TODO:Load notifications
        $CI = & get_instance();
        
        $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));

        $CI = & get_instance();
        $CI->layout = "auth";

        //validate form input
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('register_identity_label')), 'required');
        $this->form_validation->set_rules('username', str_replace(':', '', $this->lang->line('register_username_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('register_password_label')), 'required');
        $this->form_validation->set_rules('passwordrep', str_replace(':', '', $this->lang->line('register_passwordrep_label')), 'required');


        if ($this->form_validation->run() == true)
        {
            $email = $this->input->post('identity');
            $password = $this->input->post('password');
            $passwordrep = $this->input->post('passwordrep');
            $username = $this->input->post('username');
            $additional_data = array( 'first_name' => 'Prism',
                                      'last_name' => 'Poweruser');
            $group = array('1');
            
                   
            if($this->ion_auth->register($username,$password,$email,$additional_data,$group)){
                //if the register is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                
                redirect('/auth', 'refresh');
            }
            else
            {
                // if the register was un-successful
                // redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/register', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        }
        else
        {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = array('name' => 'identity',
                                            'id'    => 'identity',
                                            'type'  => 'text',
                                            'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                                            'id'   => 'password',
                                            'type' => 'password',
            );
            $this->data['passwordrep'] = array('name' => 'passwordrep',
                                               'id'   => 'passwordrep',
                                               'type' => 'password',
            );
            $this->data['username'] = array('name' => 'username',
                                            'id'   => 'username',
                                            'type' => 'text',
            );

            $this->_render_page('auth/register', $this->data);
        }
        
        $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[CONTENTE] = $this->load->view('refair-welcome',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
        $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
        $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
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

public function register($referrer=''){
redirect(base_url("/Auth/register"));
}

public function deletejob(){
//Job id will be hidden in the form
try{
if(!strcmp($data['jobid'],'NULL')){}else{
$this->data = array(
'job_id'=>$this->input->get('jobid')
);

$this->actualId = urldecode(base64_decode($this->data['job_id']));
$nameins = floor($this->actualId/10);
print_r($nameins);
$test_del = $this->jobdesc_model->get_by('id',$nameins);
$this->jobdesc_model->delete($test_del->id);

$this->session->set_flashdata('succes',"You have deleted job ".$this->actualId);
echo json_encode(array('message'=>"Sucessfull deleting of job"));
}
}catch(Exception $e){
$this->session->set_flashdata('error',"Something went wrong");
echo json_encode(array('message'=>"Job did not get deleted"));
}
}

      public function deleteref(){
          //Job id will be hidden in the form
          try{
              if(!strcmp($data['refid'],'NULL')){}else{
                  $this->data = array(
                      'refid'=>$this->input->get('refid'),
                      'refhash'=>$this->input->get('refhash')
                  );

                  $this->actualId = base64_decode($this->data['refid']);
                  $test_del = $this->jobref_model->get_by('id',$this->actualId);

                  if(is_object($test_del)){

                      if($this->jobref_model->delete($test_del->id)){
                          $this->session->set_flashdata('succes',"You have deleted");
                          echo json_encode(array('message'=>"Sucessfull deleting of referral"));
                      }else{
                          $this->session->set_flashdata('error',"You have not deleted shitt");
                          echo json_encode(array('message'=>"Nothing deleted of your referral"));
                      }
                  }else{
                      $this->session->set_flashdata('error',"Something went wrong");
                      echo json_encode(array('message'=>"Referral did not get deleted"));
                  }
              }
          }catch(Exception $e){
              $this->session->set_flashdata('error',"Something went wrong");
              echo json_encode(array('message'=>"Referral did not get deleted"));
          }
      }

      public function searchjob($hhash){
          try{
              if(!strcmp($this->input->get('title'),'')){
                  echo "<h3 class=\"jumbotron col-md-12 red\">Please specify at least the location and the jobtitle that you are looking for</i></b></h3>";
              }else{
                  $this->data = array(
                      'title'=>base64_decode($this->input->get('title')),
                      'keywords'=>base64_decode($this->input->get('keywords')),
                      'location'=>base64_decode($this->input->get('location')),
                      'fund'=>base64_decode($this->input->get('fund')),
                      'exp'=>base64_decode($this->input->get('exp'))
                  );

                  $hash=base64_decode($hhash);
                  $secarr=explode('~',$hash);
                  $email=$secarr[0];

                  $locationHash = explode('~',$this->data['location']);

                  $CI = & get_instance();
                  $CI->emailes = $email;
                  
                  $jobtitle = ($this->data['title']);
                  $jobkeywords = ($this->data['keywords']);
                  $joblocation = ($this->data['location']);
                  $jobfund = ($this->data['fund']);
                  $jobexp = ($this->data['exp']);

                  $allTheJobsBag = array();

                  $jobdescs = $this->jobdesc_model->get_all(); // this is a very fundamental constraint to take into account

                  //Orderin
                  $searchidx=0;
                  
                  foreach($jobdescs as $jobuno){
                      $searchterm=array(0,0,0,0,$jobuno->id);

                      if(strpos(strtolower($jobuno->jobtitle),strtolower($jobtitle)) !== FALSE) $searchterm[0] = 2;

                      if(!strcmp($joblocation, "Search ALL the jobs")) {
                          $searchterm[1] = 6;
                      }else if(strpos(strtolower($jobuno->location),strtolower($joblocation)) !== FALSE){
                          $searchterm[1] = 6;
                      }
                      
                      $keywordTester = preg_split('/[\s,]+/', strtolower($jobkeywords));
                      $keywordAtype = preg_split('/[\s,]+/', strtolower($jobuno->keywords));

                      $idex = 0;
                      foreach($keywordTester as $testcase){
                          foreach ($keywordAtype as $atype){
                              if($atype == $testcase){
                                  $idex+=2;
                              }
                          }
                      }
                      $searchterm[3] = $idex;

                      if($jobuno->required_fund > $jobfund){
                          $searchterm[3] -= 1;
                      }

                      // if($jobuno->required_exp < $jobexp){
                      //     $searchterm[3] -= 1;
                      // }
			      
                      if(($searchterm[0]+ $searchterm[1] + $searchterm[2] + $searchterm[3]) >= 4){
                          $allTheJobsBag[] = $searchterm;
                      }
                  }

                  array_multisort($allTheJobsBag);
                  rsort($allTheJobsBag);

                  //This is dirrrtay
                  echo "<h3 class=\"jumbotron col-md-12\">You looked for <b><i>".$jobtitle."</i></b> in <b><i>".$joblocation."</i></b> with skills <b><i>".$jobkeywords."</i></b></h3>";
                  
                  $idex = 0;
                  foreach($allTheJobsBag as $jobs){
                      $jobDesc = $this->jobdesc_model->get_by('id',$jobs[4]);
                      $this->hhhash=$hhash;
                      $this->printJob($jobDesc);
                      $idex++;
                  }

                  $this->session->set_flashdata('succes',"Searching ".$jobtitle);
              }
          }catch(Exception $e){
              $this->session->set_flashdata('error',"Something went wrong");
          }
      }

      public function newpagination(){
          echo "<p>".$this->jobdesc_model->all_pages."</p>";
          echo "<p>".$this->jobdesc_model->previous_page."</p>";
          echo "<p>".$this->jobdesc_model->next_page."</p>";
      }
      
    public function addjobdesc(){
        //Job id will be hidden in the form
        try{
            $jobdescdata = array(
                'id' => NULL,
                'jobtitle' => base64_decode($this->input->get('jobtitle')),
                'required_exp' => base64_decode($this->input->get('required_exp')),
                'required_fund' =>base64_decode( $this->input->get('required_fund')),
                'required_relocation' => base64_decode($this->input->get('required_relocation')),
                'required_remote' => base64_decode($this->input->get('required_remote')),
                'regdate'=>NULL, //calculated in model
                'keywords' => base64_decode($this->input->get('keywords')),
                'location' => base64_decode($this->input->get('location')),
                'description' => base64_decode($this->input->get('description')),
                'poster_id'=>base64_decode($this->input->get('poster_id')),
                'bounty'=>base64_decode($this->input->get('bounty_id')),
                'hash' => NULL //calculated in model
            );
            
            if($this->jobdesc_model->insert($jobdescdata)){
                $this->session->set_flashdata('message',"You have added jobdesc to location of hash ".$_GET['location']);
                echo json_encode(array('message',"You have sucessfully added a jobd to location:".$_GET['location']));
            }else{
                echo json_encode(array('message',"You have failed adding a job"));
            }
        }catch(Exception $e){
            echo json_encode(array('message',"You met an exception:".$e->value));
        }
        
    }
    
    public function delperk(){
        //Job id will be hidden in the form
        try{
            if(!strcmp($data['perkid'],'NULL')){}else{
                $perkid = $this->input->get('perkid');
                $perkuid = $this->input->get('perkuid');
                
                  $this->test_del = $this->perk_model->get($perkid);
                  
                  if(is_object($this->test_del)){
                      if($this->perk_model->delete($this->test_del->id)){
                          $this->session->set_flashdata('message',"You have deleted your perk succesfully");
                          echo json_encode(array('message'=>"Sucessfull deleting of perk"));
                      }else{
                          $this->session->set_flashdata('message',"You have not deleted and something went wrong");
                          echo json_encode(array('message'=>"Something went wrong when deleting the perk"));
                      }
                  }else{
                      $this->session->set_flashdata('message',"That perk does not exist in the DB: ");
                      echo json_encode(array('message'=>"Something went wrong when deleting the perk 2 -- ". $perkid.$perkuid));
                  }
              }
          }catch(Exception $e){
              $this->session->set_flashdata('error',"Something went wrong while deleting the perk");
              echo json_encode(array('message'=>"Something went wrong deleting the perk"));
          }
      }
      
      
      public function addperk(){
          //Job id will be hidden in the form
          try{
              if(!strcmp($this->input->get('newperk-name'),'')){}else{
                  $this->data = array(
                      'id'=>NULL,
                      'jobid' => $this->input->get('newperk-jobid'),
                      'name' => $this->input->get('newperk-name'),
                      'uid'=>$this->input->get('newperk-posterid'),
                      'agreed_employer' => 1, //agreed from job giver
                      'agreed_employee' => NULL, //agreed from job taker or referee
                      'hash' => NULL ,//calculated in model
                      'regdate' => NULL, //calculated in model
                      'target' =>$this->input->get('newperk-target'),
                      'agreed_referee' => NULL, //agreed from referral
                      
  );
  
  $test = $this->perk_model->get_all();
  $this->perk_model->insert($this->data);
  $this->session->set_flashdata('succes'," You have added a new perk to jobid ".$this->data['jobid'] );
  }
  }catch(Exception $e){
  $this->session->set_flashdata('error',"Something went wrong");
  }
  }

  
      public function refer(){
          //Job id will be hidden in the form
          try{
              if(!strcmp($this->input->get('role'),'')){}else{
                  $this->data = array(
                      'role' => $this->input->get('role'),
                      'referrer' => $this->input->get('referrer'),
                      'referred' => $this->input->get('referred'),
                      'location' => $this->input->get('location'),
                      'keywords' => $this->input->get('keywords'),
                      'jobid' => $this->input->get('jobid')
                  );
  
                  $jobrefe = array(
                      'id'=>NULL,
                      'referred_id'=>$this->data['referred'],
                      'location_id'=>$this->data['location'],
                      'name'=>$this->data['role'],
                      'keywords'=>$this->data['keywords'],
                      'regdate'=>NULL,
                      'hash'=>NULL,
                      'state'=>"MAILSHAKE",
                      'jobid'=>$this->data['jobid'],
                      'referrer_id'=>$this->data['referrer'],
                  );
  
                  print_r($jobrefe);
                  //EMAIL SENDING HAPPENS HERE
                  $this->jobref_model->insert($jobrefe);
                  echo "YOU HAVE SUCESFULLY REFAIRED ".$jobrefe['referred_id']." FOR A POSITION";

                  $message = $jobrefe['keywords'];

                  $referred_mail = $jobrefe['referred_id'];
                  $referrer_mail = $jobrefe['referrer_id']; //Aha! xP
  
                  $jobid = $jobrefe['jobid'];
                  $location = $jobrefe['location_id'];
                  $jobRole = $jobrefe['name'];
  
                  $configsa = array();
                  $configsa['useragent']           = "CodeIgniter";
                  $configsa['mailpath']            = "/usr/bin/sendmail -t -i"; // or "/usr/sbin/sendmail"                 $configsa['protocol']            = "ssmtp";
                  $configsa['smtp_host']           = "localhost";
                  $configsa['smtp_port']           = "25";
                  $configsa['mailtype'] = 'html';
                  $configsa['charset']  = 'utf-8';
                  $configsa['newline']  = "\r\n";
                  $configsa['wordwrap'] = TRUE;
  
                  $this->load->library('email',$configsa);
                  $this->email->initialize($configsa);
  
                  $this->email->from("piotr.slupski@pryzmat.pl",'Hello from refair.me!');
                  $arrayOfEmail = array($referrer_mail,$referred_mail,"piotroxp@o2.pl");
                  $this->email->to($arrayOfEmail);
                  $this->email->subject('A referral has begun! '.$referred_mail.' has been referred for a position by '.$referrer_mail );
                  $msg = 'Hello! Refair.me lets you take action on your job offers by creating a referral.<br/> <strong>'.$referred_mail.' had been referred</strong> for a job position just now. <br/><h2>A candidate had been found for your job post, '.$referred_mail.' <i>, and '.$referrer_mail.' thinks its a  match.</h2> <br/>The job id is:<strong></i>'.$jobid.'<br/></strong>. Your job post is '.$jobRole.'. The keywords used by the referee for this referral: <strong>'.$message.'</strong><br/>The job is located in <strong>'.$location.'</strong>';
                  $this->email->message($msg);
  
                  if($this->email->send())
                  {
                      echo "<div class=\"jumbotron content-main\">Email sent. Thank you for your interest, ".$email_name."</div>";
                  }
                  else
                  {
                      show_error($this->email->print_debugger());
                  }
              }
          }catch (Exception $e){
              echo "Jobref had error";
          }
      }

      private function shortString($yourString,$maxsize){
          if (strlen($yourString) > $maxsize) // if you want...
          {
              $maxLength = $maxsize;
              $yourString = substr($yourString, 0, $maxLength).'...';
          }
          return $yourString;
      }
  
      private function printJob($value){ 
          echo "<div style=\"margin-top:20px\"/>";
          if(count($value) == 0){
              echo "<h1>Unfortunately, your search term returned no results. Please re-ask your search term properly or change your keywords</h1><br/>";
          }else{
              $CI = & get_instance();
              $this->emailes = $CI->emailes;
              $this->userAble = false; //Placholder for general user printing with a single variable 
              ?>
<div class="silver" style="height:650px;width:350px;float:left;margin:15px;display:block;padding:15px;">
  <div class="refair-search-result" style="text-align:left;overflow:hidden;display:block;">
    <div class="boxes">Registered: <?php echo $value->regdate; ?></div>
    <div style="boxes">
      <p><div class="silver" id="jobtitle" style="padding:13px;width:100%"><h3><?php echo $value->jobtitle ; ?></div></h3></p>
      <h2 style="margin-top:15px !important;"><a href="<?php echo base_url('Refair/describejob/'.$this->hhhash.'/'.$value->id); ?>">View </a></h2>
    </div>
    <div class="boxes">Location:<b> <div class="green" id="location"><?php echo $value->location; ?></div></b></div>
    <div class="boxes">Experience required: <b><div class="green"  id="required_exp"><?php echo $value->required_exp; ?></div></b></div>
    <div class="boxes" >
      <div class="" style="float:left;">Relocation: <b><div  class="green" id="required_relocation"><?php echo $value->required_relocation; ?></div></b></div>
      <div class="" style="float:right;">Remote: <b><div  class="green" id="required_remote"><?php echo $value->required_remote; ?></div></b></div>
    </div>
    <div class="boxes">Keywords: <b><div  class="green" id="keywords"><?php echo $this->shortString($value->keywords,23); ?></div></b></div>
  </div>
  <form style="width:100%;margin-bottom:5px;padding-bottom:5px;padding-right:5px;display:block;overflow:hidden;">
    <div  style="float:left;margin-left:14px;">      
      <p name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>"  class="btn btn-raised active refair-call" data-target="#refer-body" data-toggle="modal">Refer someone<div class="ripple-container"></div></p>
    </div>
    <div  style="float:left;margin-left:14px;">      
      <p name="<?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>" class="btn btn-raised active apply-call" data-target="#apply-body" data-toggle="modal">Apply<div class="ripple-container"></div></p>
    </div>
    <?php      if($this->userAble){ ?>
    <div  style="float:left;margin-left:14px;">      
      <p name="<?php echo $value->id.'~'.$value->poster_id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location.'~'.$this->emailes; ?>" class="btn btn-raised active deletejob-call" data-target="#delete-jobss" data-toggle="modal">Delete<div class="ripple-container"></div></p>
    </div>
    <?php      } ?>
    <div class="hidden" id="abbrev"><?php echo $value->id.'~'.$value->jobtitle.'~'.$value->keywords.'~'.$value->location; ?></div>
  </form>
</div>
     <?php
      }
  }
      
  function _processSite($site){
  
  }

  public function account(){
  redirect(base_url("account"));
  }

      public function describereferral($hhash,$refid=0){
          $hash=base64_decode($hhash);
          $secarr=explode('~',$hash);
          $email=$secarr[0];
          $usuario = $this->user_model->get_by('email',$secarr[0]);

          $CI = & get_instance();
          $CI->active = password_verify($secarr[2],$secarr[1]);
          $CI->layout="auth";

          $this->styles = array("style");
          $this->menus = array("Refair/secure/".$hhash."/about~About us",
                               "Refair/secure/".$hhash."/contact~Contact us",
                               "Refair/secure/".$hhash."~Refair",
                               "Account/secure/".$hhash."~Dashboard",
                               "Auth/logout~Logout now");
          $this->lowermenus = array("Refair/secure/".$hhash."/about~About us",
                                    "Refair/secure/".$hhash."/contact~Contact us",
                                    "Refair/secure/".$hhash."~Refair",
                                    "Account/secure/".$hhash."~Dashboard");

          $this->dataReferrals = $this->jobref_model->get_many_by('id',$refid);
          $this->refid = $refid;
          $this->dataJobs = $this->jobdesc_model->get((int)$this->dataReferrals[0]->jobid);
          $this->jobDescs = $this->jobdesc_model->get((int)$this->dataReferrals[0]->jobid);
          $datLocation = $this->dataReferrals[0]->location_id;
          $this->jobRefLocation = $this->location_model->get_by('name',$datLocation);
          $jobid = $this->dataReferrals[0]->jobid;
          $this->jobid = $jobid;
          $this->hhhash = $hhash;
          $this->uid = $usuario->id;
          $this->emailes = $usuario->email;
          $this->userAble = false;
          $this->jobReferral = $this->jobdesc_model->get((int)$this->dataReferrals[0]->jobid);
          
          if( !strcmp($this->dataJobs->poster_id, $email) ){
              $this->userAble = true;
          }

          $this->dataPerks = $this->perk_model->get_many_by('uid',$this->uid);
          $newperks=array();
          foreach ($this->dataPerks as $value){
              if($value->jobid == $this->jobid){
                  $newperks[] = $value;
              }
          }
          $this->dataPerks = $newperks;

          $this->thirdPartyPerks = $this->perk_model->get_many_by('jobid',$jobid);
          
          $this->pasdat = array($this->thirdPartyPerks,
                                $this->jobReferral,
                                $this->jobRefLocation,$this->jobDescs,
                                $this->userAble,$this->menus,$this->lowermenus,$this->dataJobs, $this->dataPerks,$this->jobid,$this->uid,$this->emailes,$this->hhhash);
          //TODO:Load data for popular searches
          //TODO:Load data for popular users
          //TODO:Load notifications
          $CI = & get_instance();

          $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));

          $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
          $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
          $this->parts[MAPPLUGINREF] = $this->load->view('mapplugin',$this->pasdat,true);
          $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
          $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
          $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
          $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
          $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
          $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
          $this->parts[CONTENTE] = $this->load->view('refair-referral',$this->pasdat,true);
          $this->parts[REFAIRPERK] = $this->load->view('refair-describeperk',$this->pasdat,true);
          $this->parts[REFAIRLOCATION] = $this->load->view('refair-location',$this->pasdat,true);
          $this->parts[REFAIRTRACKREF] = $this->load->view('refair-trackreferral',$this->pasdat,true);
          $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
          $this->parts[MODALDELPERK] = $this->load->view('modal-deleteperk',$this->pasdat,true);
          $this->parts[MODALDELETEREF] = $this->load->view('modal-deleteref',$this->pasdat,true);
          $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
          $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
          $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
      }

      public function describejob($hhash,$jobid=0){
          $hash=base64_decode($hhash);
          $secarr=explode('~',$hash);
          $email=$secarr[0];
          $this->hhhash = $hhash;
          
          $usuario = $this->user_model->get_by('email',$secarr[0]);

          $CI = & get_instance();
          $CI->active = password_verify($secarr[2],$secarr[1]);
          $CI->layout="auth";

          $this->styles = array("style");
          $this->menus = array("Refair/secure/".$hhash."/about~About us",
                               "Refair/secure/".$hhash."/contact~Contact us",
                               "Refair/secure/".$hhash."~Refair",
                               "Account/secure/".$hhash."~Dashboard",
                               "Auth/logout~Logout now");
          $this->lowermenus = array("Refair/secure/".$hhash."/about~About us",
                                    "Refair/secure/".$hhash."/contact~Contact us",
                                    "Refair/secure/".$hhash."~Refair",
                                    "Account/secure/".$hhash."~Dashboard");

          $this->dataJobs = $this->jobdesc_model->get_many_by('id',$jobid);
          $this->jobid = $jobid;
          $this->uid = $usuario->id;
          $this->emailes = $usuario->email;
          $this->userAble = false;

          if(count($this->dataJobs)>=1){
              if( !strcmp($this->dataJobs[0]->poster_id, $secarr[0]) ){
                  $this->userAble = true;
              }
          }else{
              ?>
                <h1 class="jumbotron red col-md-12">There is nothing to show</h1>
              <?php
          }
              
          $this->dataPerks = $this->perk_model->get_many_by('uid',$this->uid);
          $newperks=array();
          foreach ($this->dataPerks as $value){
              if($value->jobid == $this->jobid){
                  $newperks[] = $value;
              }
          }
          $this->dataPerks = $newperks;

          $this->pasdat = array($this->hhhash,
                                $this->userAble,
                                $this->menus,
                                $this->lowermenus,
                                $this->dataJobs,
                                $this->dataPerks,
                                $this->jobid,
                                $this->uid,
                                $this->emailes);
          //TODO:Load data for popular searches
          //TODO:Load data for popular users
          //TODO:Load notifications
          $CI = & get_instance();

          $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));

          $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
          $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
          $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
          $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
          $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
          $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
          $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
          $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
          $this->parts[CONTENTE] = $this->load->view('refair-describejob',$this->pasdat,true);
          $this->parts[REFAIRPERK] = $this->load->view('refair-describeperk',$this->pasdat,true);
          $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
          $this->parts[MODALDELPERK] = $this->load->view('modal-deleteperk',$this->pasdat,true);
          $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
          $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
          $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
      }

    public function describelocation($hhash,$locid=0){
        $hash=base64_decode($hhash);
        $secarr=explode('~',$hash);
        $email=$secarr[0];
        
        $usuario = $this->user_model->get_by('email',$secarr[0]);
        
        $CI = & get_instance();
        $CI->active = password_verify($secarr[2],$secarr[1]);
        $CI->layout="auth";
        
        $this->styles = array("style");
        $this->menus = array("Refair/secure/".$hhash."/about~About us",
                             "Refair/secure/".$hhash."/contact~Contact us",
                             "Refair/secure/".$hhash."~Refair",
                             "Account/secure/".$hhash."~Dashboard",
              "Auth/logout~Logout now");
        $this->lowermenus = array("Refair/secure/".$hhash."/about~About us",
                                  "Refair/secure/".$hhash."/contact~Contact us",
                                  "Refair/secure/".$hhash."~Refair",
                                  "Account/secure/".$hhash."~Dashboard");
        
        $location_data = $this->location_model->get_by('id',$locid);
        
        $this->pasdat = array($this->menus,$this->lowermenus,$location_data,$hhash);
        //TODO:Load data for popular searches
        //TODO:Load data for popular users
        //TODO:Load notifications
        
        $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));
        
        $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[CONTENTE] = $this->load->view('refair-location',$this->pasdat,true);
        $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
        $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
        $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
    }
    
    private function describeimports($hhash,$page=1){
        $hash=base64_decode($hhash);
        $secarr=explode('~',$hash);
        $email=$secarr[0];
        $CI = & get_instance();
        $CI->active = password_verify($secarr[2],$secarr[1]);
        $CI->layout="auth";

        $usuario = $this->user_model->get_by('email',$secarr[0]);
        $this->styles = array("style");
        $this->menus = array("Refair/secure/".$hhash."/about~About us",
                             "Refair/secure/".$hhash."/contact~Contact us",
                             "Refair/secure/".$hhash."~Refair",
                             "Account/secure/".$hhash."~Dashboard",
                             "Auth/logout~Logout now");
        $this->lowermenus = array("Refair/secure/".$hhash."/about~About us",
                                  "Refair/secure/".$hhash."/contact~Contact us",
                                  "Refair/secure/".$hhash."~Refair",
                                  "Account/secure/".$hhash."~Dashboard");

        $total_row = $this->linkedin_import_model->db->count_all_results();
        $this->dataLocations = $this->location_model->get_all();
        $this->hhhash = $hhash;
        $this->emailes=$email;

        $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));

        $config = array();
        $config["base_url"] = base_url() . "/Refair/describeimports/".$hhash;
        $config["total_rows"] = $total_row;
        $config["per_page"] = 10;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = $total_row;
        $config['cur_tag_open'] = '&nbsp;<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['uidInviter'] = $usuario->email;

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        if($this->uri->segment(6)){
            $page = ($this->uri->segment(6)) ;
        }
        else{
            $page = 1;
        }

        $this->paginatedResults = $this->linkedin_import_model->fetch_data($config["per_page"], $page, $config['uidInviter']);
        $str_links = $this->pagination->create_links();
        echo $str_links;
        $this->pagLinks = array(explode('&nbsp;',$str_links ));

        $this->pasdat = array($this->pagLinks, $this->paginatedResults,
                              $this->emailes,$this->menus,
                              $this->lowermenus,$this->hhhash,
                              $this->dataLocations);
        // View data according to array.
        $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[CONTENTE] = $this->load->view('refair',$this->pasdat,true);
        $this->parts[REFAIRJOBS] = $this->load->view('refair-listimports',$this->pasdat,true);
        $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
        $this->parts[REFAIRHORIZON] = $this->load->view('horizon/refair-horizon',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
        $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
        $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
    }

    public function upload()
    {
        $hhash = $_POST['abbrev'];
        $hash=base64_decode($hhash);
        $secarr=explode('~',$hash);

        print_r($hhash);
        
        $this->emailes=$secarr[0];
        
        $status = "";
        $msg = "";
        $file_element_name = 'fileli';

        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'csv|txt';
        $config['max_size'] = 1024 * 8192;
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload($file_element_name))
        {
            $status = 'error';
            $msg = $this->upload->display_errors('', '');
        }
        else
        {
            $data = $this->upload->data();
            
            $this->filedata = array(
                'id' => NULL,
                'filename' => $data['file_name'],
                'title' => $_POST['filename-monit'],
                'hash'=>$hhash,
                'regdate'=>null
            );
            
            $file_id = $this->file_model->insert($this->filedata);
            
            $csvData = array_map('str_getcsv', file('./upload/'.$data['file_name']));
            
            foreach($csvData as $import){
                try{
                    if($import[0]==NULL){
                        continue;
                    }
                    if($import[1]==NULL){
                        continue;
                    }
                    if($import[2]==NULL){
                        continue;
                    }
                                        
                    $linkedin_import = array(
                        'id'=>NULL,
                        'firstName'=>$import[0],
                        'lastName'=>$import[1],
                        'company'=>$import[2],
                        'title'=>$import[3],
                        'email'=>$import[4],
                        'phone'=>$import[5],
                        'notes'=>$import[6],
                        'tags'=>$import[7],
                        'uidInviter'=>$this->emailes,
                        'regdate'=>NULL
                    );
                    
                    if( !$this->linkedin_import_model->insert($linkedin_import) ){
                        throw "Something was fucked while importing";
                    }
                }catch(Exception $e){
                    print_r("EXCEPTIN");
                    $msg = "Wrong row in imports";
                }
            }
            
            if($file_id)
            {
                $status = "success";
                $msg = "File successfully uploaded";
            }
            else
            {
                unlink($data['full_path']);
                $status = "error";
                $msg = "Something went wrong when saving the file, please try again.";
            }
        }
        @unlink($_FILES[$file_element_name]);
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
    
    public function notifications(){
        $this->styles = array("style");
        $this->menus = array("What","Who","Why","Register");
        $this->pasdat = array($this->menus);
        $CI = & get_instance();
        $CI->layout="default_layout";
        $this->parts[TITLE] = $this->load->view('title-research',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[NOTIFICATIONS] = $this->load->view('notifications',$this->pasdat,true);
        $this->parts[FOOTER] = $this->load->view('header',$this->pasdat,true);
    }

    public function notificationslist(){
        $this->styles = array("style");
        $this->menus = array("What","Who","Why","Register");
        $this->pasdat = array($this->menus);
        $CI = & get_instance();
        $CI->layout="default_layout";
        $this->parts[TITLE] = $this->load->view('title-research',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[NOTIFICATIONSLIST] = $this->load->view('notificationslist',$this->pasdat,true);
        $this->parts[FOOTER] = $this->load->view('header',$this->pasdat,true);
    }

    public function secure($hhash,$site='')
    {
        if(!strcmp($site,'')){
            $hash=base64_decode($hhash);
            $secarr=explode('~',$hash);
            $this->emailes=$secarr[0];
            $this->hhhash = $hhash;

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
                                 "Refair/secure/".$hhash."/describeimports/".$hhash."~Your LinkedIn imports",
                                 "Refair/secure/".$hhash."/referrals~Your Referrals",
                                 "Auth/logout~Log out"); //should be a function that overwrites the default so we don't bother most of the time
            $this->lowermenus = $this->menus;

            $this->dataJobs = $this->jobdesc_model->get_all();
            $this->dataReferrals = $this->jobref_model->get_many_by('referrer_id',$usuario->email);
            $this->dataReferralsReferred = $this->jobref_model->get_many_by('referred_id',$this->emailes); //Referred other ppl
            $this->dataReferralsReferrer = $this->jobref_model->get_many_by('referrer_id',$this->emailes); //Referred BY other ppl
            $this->dataApplications = array();
            
            foreach($this->dataReferralsReferrer as $value){
                if(!strcmp($value->referred_id,$value->referrer_id)) $this->dataApplications[]=$value;
            }

            $this->dataLocations = $this->location_model->get_all();
            
            $this->userAble = true; //we are in the accounts dashboard we see all
            
            $this->pasdat = array($this->dataApplications,
                                  $this->dataReferralsReferrer,
                                  $this->dataReferralsReferred,
                                  $this->userAble,
                                  $this->menus,
                                  $this->emailes,
                                  $this->dataJobs,
                                  $this->hhhash,
                                  $this->dataReferrals,
                                  $this->dataLocations);
            
            if($this->ion_auth->logged_in()){            
                $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));
            }

            $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
            $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
            $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
            $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
            $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
            $this->parts[CONTENTE] = $this->load->view('refair',$this->pasdat,true);
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
            switch ($site){
            case 'about' : $this->about($hhash);break;
            case 'contact' : $this->contact($hhash);break;
            case 'jobs' : $this->jobs($hhash); break;
            case 'referrals' : $this->referrals($hhash);break;
            case 'describelocation' : $this->describelocation($hhash); break;
            case 'describeimports' : $this->describeimports($hhash); break;
            default : $this->index();break;
            }
        }
    }

    public function about($hhash){
        $hash=base64_decode($hhash);
        $secarr=explode('~',$hash);
        $email=$secarr[0];
        
        $usuario = $this->user_model->get_by('email',$secarr[0]);
        
        $this->styles = array("style");
        $this->menus = array("Refair/secure/".$hhash."/about~About us",
                             "Refair/secure/".$hhash."/contact~Contact us",
                             "Refair/secure/".$hhash."~Refair",
                             "Account/secure/".$hhash."~Dashboard",
                             "Auth/logout~Logout now");
        $this->lowermenus = array("Refair/secure/".$hhash."/about~About us",
                                  "Refair/secure/".$hhash."/contact~Contact us",
                                  "Refair/secure/".$hhash."~Refair",
                                  "Account/secure/".$hhash."~Dashboard");
                    
        $dashJobs = $this->jobdesc_model->get_all();
                    
        $this->pasdat = array($this->menus,$this->lowermenus,$dashJobs);
        //TODO:Load data for popular searches
        //TODO:Load data for popular users
        //TODO:Load notifications
        $CI = & get_instance();
                    
        $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));
                    
                    
        $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
        $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
        $this->parts[MODALDELETEREF] = $this->load->view('modal-deleteref',$this->pasdat,true);
        $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        echo "<h1> ABOUT </h1>";
    }

    public function contact($hhash){
            $hash=base64_decode($hhash);
            $secarr=explode('~',$hash);
            $this->emailes=$secarr[0];
            $this->hhhash = $hhash;

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
                                 "Refair/secure/".$hhash."/describeimports/".$hhash."~Your LinkedIn imports",
                                 "Refair/secure/".$hhash."/referrals~Your Referrals",
                                 "Auth/logout~Log out"); //should be a function that overwrites the default so we don't bother most of the time
            $this->lowermenus = $this->menus;

            $this->dataJobs = $this->jobdesc_model->get_all();
            $this->dataReferrals = $this->jobref_model->get_many_by('referrer_id',$usuario->email);
            $this->dataReferralsReferred = $this->jobref_model->get_many_by('referred_id',$this->emailes); //Referred other ppl
            $this->dataReferralsReferrer = $this->jobref_model->get_many_by('referrer_id',$this->emailes); //Referred BY other ppl
            $this->dataApplications = array();
            
            foreach($this->dataReferralsReferrer as $value){
                if(!strcmp($value->referred_id,$value->referrer_id)) $this->dataApplications[]=$value;
            }

            $this->dataLocations = $this->location_model->get_all();
            
            $this->userAble = true; //we are in the accounts dashboard we see all
            
            $this->pasdat = array($this->dataApplications,
                                  $this->dataReferralsReferrer,
                                  $this->dataReferralsReferred,
                                  $this->userAble,
                                  $this->menus,
                                  $this->emailes,
                                  $this->dataJobs,
                                  $this->hhhash,
                                  $this->dataReferrals,
                                  $this->dataLocations);
            
            if($this->ion_auth->logged_in()){            
                $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));
            }

        echo "<h1> CONTACT </h1>";
        $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
        $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
        $this->parts[MODALDELETEREF] = $this->load->view('modal-deleteref',$this->pasdat,true);
        $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);

        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
    }
    
    public function jobs($hhash){
        $hash=base64_decode($hhash);
        $secarr=explode('~',$hash);
        $email=$secarr[0];
        
        $usuario = $this->user_model->get_by('email',$secarr[0]);

        $this->styles = array("style");
        $this->menus = array("Refair/secure/".$hhash."~Refair",
                             "Refair/secure/".$hhash."/jobs~All Jobs",
                             "Refair/secure/".$hhash."/referrals~Your Referrals",
                             "Account/secure/".$hhash."~Dashboard",
                             "Auth/logout~Logout now");
        $this->lowermenus = array("Refair/secure/".$hhash."/about~About us",
                                  "Refair/secure/".$hhash."/contact~Contact us",
                                  "Refair/secure/".$hhash."~Refair",
                                  "Account/secure/".$hhash."~Dashboard");

        $this->dataJobs = $this->jobdesc_model->get_all(); // very very important end case
        $this->dataLocations = $this->location_model->get_all();
        $this->hhhash = $hhash;
        $this->emailes = $email;

        $this->userAble=false; //We do not want editing here
        
        $this->pasdat = array($this->emailes,
                              $this->userAble,
                              $this->menus,
                              $this->lowermenus,
                              $this->dataJobs,
                              $this->hhhash,
                              $this->dataLocations);
        //TODO:Load data for popular searches
        //TODO:Load data for popular users
        //TODO:Load notifications
        $CI = & get_instance();
        
        $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">".$email."</a>. You agree to cookies and you are looking at the jobs."));
        
        $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
        $this->parts[REFAIRHORIZON] = $this->load->view('horizon/refair-horizon',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[CONTENTE] = $this->load->view('refair',$this->pasdat,true);
        $this->parts[REFAIRJOBS] = $this->load->view('refair-sysjobs',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
        $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
        $this->parts[MODALDELETEREF] = $this->load->view('modal-deleteref',$this->pasdat,true);
        $this->parts[MODALADDLOCATION] = $this->load->view('modal-addlocation',$this->pasdat,true);
        $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
        $this->parts[MODALDELETELOCATION] = $this->load->view('modal-deletelocation',$this->pasdat,true);
        $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[FLASH] = $this->load->view('flash',$this->pasdat,true);
    }
    
    public function referrals($hhash){
        //TODO - from secure in auth
            $hash=base64_decode($hhash);
            $secarr=explode('~',$hash);
            $email=$secarr[0];
            $usuario = $this->user_model->get_by('email',$secarr[0]);
            $this->emailes = $email;

            $CI = & get_instance();
            $CI->active = password_verify($secarr[2],$secarr[1]);
            $CI->layout="auth";
            $this->hhhash=$hhash;
            
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
            
            $this->dataJobs = $this->jobdesc_model->get_all();
            $this->dataLocations = $this->location_model->get_all();
            $this->dataReferrals = $this->jobref_model->get_many_by('referrer_id',$email);
            $this->dataReferralsReferred = $this->jobref_model->get_many_by('referrer_id',$this->emailes);
            $this->dataReferralsReferrer = $this->jobref_model->get_many_by('referred_id',$this->emailes); //Referred BY other ppl

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
            $this->parts[CONTENTE] = $this->load->view('refair',$this->pasdat,true);
            $this->parts[REFAIRJOBS] = $this->load->view('refair-referrals',$this->pasdat,true);
            $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
            $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
            $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
            $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true);
            $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
            $this->parts[MODALDELETEREF] = $this->load->view('modal-deleteref',$this->pasdat,true);
            $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
            $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
            $this->parts[FLASH] = $this->load->view('flash',$this->pasdat,true);
    }
    
    
}
