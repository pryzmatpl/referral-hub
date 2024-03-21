<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use DDG\API\Api;
use DDG\API\Http\Listener\JsonBodyListener;

//Define the structure of the layout
define('HEAD', "head");
define('CONTENTE', "contente");
define('FOOTER', "footer");
define('META', "meta");
define('SUBMIT', "submit");
define('SCRIPTS',"scripts");
define('TITLE',"title");
define('PARTS',"parts");
define('POPULAR',"popular");
define('FAME',"fame");
define('NOTIFICATIONS',"notifications");
define('ERROR',"error");
define('WAITING', "waiting");
define('FLASH', "flash");
define('QUOTES',"quotes");
define('CONTACT',"contact");
define('ABOUT',"about");
define('SLAVINGWAY',"slavingway");
define('COOKIE',"cookie");

define('NOTIFICATIONSLIST',"notificiationslist");
define('POPULARLIST',"popularlist");
define('FAMELIST',"famelist");

class Slavingway extends CI_Controller {

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
		$this->load->database();
        $this->load->library(array('ion_auth','session'));
		$this->load->helper(array('url','language'));
        $this->lang->load('auth');

        $CI = & get_instance();
        $CI->layout="auth";
    }

    public function index()
    {
        $this->styles = array("style");
        $this->menus = array("#about~About us",
                             "#contact~Contact us",
                             "Postwriter~PostWriter",
                             "Research~Research Tool");
        $this->lowermenus = array("#about~About us",
                             "#contact~Contact us",
                             "Postwriter~Writing Services",
                             "Research~Research Tool");
        
        $this->pasdat = array($this->menus,$this->lowermenus);
        //TODO:Load data for popular searches
        //TODO:Load data for popular users
        //TODO:Load notifications
        $CI = & get_instance();
        
        $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));

        $this->parts[TITLE] = $this->load->view('title',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[CONTENTE] = $this->load->view('slavingway',$this->pasdat,true);
        $this->parts[ABOUT] = $this->load->view('about',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[FLASH] = $this->load->view('flash',$this->pasdat,true);
    }

    function logoff(){
        $this->ion_auth->logout();
        redirect(base_url());
    }
    
    public function about(){
        $this->index();
        $this->parts[ABOUT] = $this->load->view('about',$this->pasdat,true);
        
    }
    
    public function contact(){
        $this->index();
        $this->parts[CONTACT] = $this->load->view('contact',$this->pasdat,true);
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

    public function logouter($hash){
        $email=urldecode(base64_decode($hash));
        $CI = & get_instance();
        $CI->active=true;
        $this->index();
    }

    public function justquery($strings){
        $strings = base64_decode($strings);
        $arr = explode('~',$strings);
        $key1=$arr[0];
        $key2=$arr[1];
        $key3=$arr[2];
        $str=$arr[3];

        $this->session->set_flashdata('message', array('Register please or login'));

        $this->_enableRequests();

        $this->styles = array("style");
        $this->menus = array("Auth/login~Login",
                             "Auth/register~Register",
                             "Welcome/tutorial~Tutorial");
        $this->pasdat = array($this->menus);
        //TODO:Load data for popular searches
        //TODO:Load data for popular users
        //TODO:Load notifications
        $CI = & get_instance();

        
        $this->parts[TITLE] = $this->load->view('title',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[FLASH] = $this->load->view('flash',$this->pasdat,true);
        
        Requests::register_autoloader();
        $headers = array('Accept' => 'application/json');
        $goo = Requests::get("https://www.googleapis.com/customsearch/v1?key=AIzaSyBbIBg8XouqPNQ4dFe1cvOF3dMx-0LUi-E&cx=014153420191187252986:u6d2kyoanh0&q=".urlencode($str)."&count=1149",$headers)->body;
        $items=json_decode($goo);
        
        $ddg = new Api();
        $ddg->getClient()->addListener(new JsonBodyListener());
        $responsenext = $ddg->api('Disambiguation')->get($key1)->getContent();
        
        $headers=array('Ocp-Apim-Subscription-Key' => '30bb684dee654c4b97118f89692f69e6',);
        $parameters = array('q'=>urlencode($str),
                            'count'=>'1149',
                            'offset'=>'0',
                            'mkt'=>'en-us',
                            'safesearch' => 'Off');
        $responsems = Requests::get("https://api.cognitive.microsoft.com/bing/v5.0/search?q=".$parameters['q']."&count=".$parameters['count']."&offset=".$parameters['offset']."&mkt=".$parameters['mkt']."&safesearch=".$parameters['safesearch'],$headers)->body;
        $itemsms=json_decode($responsems);
        
        $itemsddg = $responsenext->RelatedTopics;
        $itemsmsf = $itemsms->webPages->value;

        $gooErrMsgNo = "<div class=\"error results-list-item\">Google error</div>";

        $itemsgoo = array();
        $itemsgoo = $items->items;
        
        $sites_raw = array();
        $links = array();
        $shorts = array();
        $titles = array();
        $errors = array();
        $vendor = array();
        
        $gooErrMsg = "<div class=\"error results-list-item\">Google API error";
        $ddgErrMsg = "<div class=\"error results-list-item\">DDG API error";
        $msfErrMsg = "<div class=\"error results-list-item\">Bing API error";
        
        if( count($itemsgoo) > 0){
            foreach($itemsgoo as $item){
                try{
                    $sites_raw[$item->formattedUrl] = Requests::get($item->link,array(),array('timeout'=>'2.72'))->body;
                    usleep(100);
                    $links[] = $item->link;
                    $shorts[] = $item->htmlSnippet;
                    $titles[] = $item->title;
                    $vendor[] = "goo";
                }catch(Exception $e){
                    $errors[]=$gooErrMsg." timeout on direct connection</div>"; //DO NEVER CODE LiKE ThiS !!!!! I'm so sorry 
                }
            }} else{
            $errors[]=$gooErrMsg." no results </div>";
        }
        
        if(count($itemsddg)>0){
            foreach($itemsddg as $item=>$resutl){
                if( isset ($resutl->FirstURL) ){
                    $sites_raw[$resutl->FirstURL] = Requests::get($resutl->FirstURL,array(),array('timeout'=>'2.72'))->body;
                    usleep(100);
                    $links[] = (string)$resutl->FirstURL;
                    $shorts[]=(string)$resutl->Result;
                    $titles[]=(string)$resutl->Text;
                    $vendor[]="ddg";
                }}}else{
            $errors[]= $ddgErrMsg." no results </div>";
        }
        
        if(count($itemsmsf)>0){
            foreach($itemsmsf as $item=>$resutl){
                usleep(100);
                try{
                    $sites_raw[$resutl->displayUrl] = Requests::get($resutl->url,array(),array('timeout'=>'2.72'))->body; //don be an idiot anymore, Boywonder
                    $links[] = $resutl->url;
                    $shorts[] = "MediocreSofts API:".$resutl->name;
                    $titles[] = $resutl->name;
                    $vendor[]="msf";
                } catch(Exception $e ) {
                    $errors[]=$msfErrMsg." error on direct connection</div>";
                }}}else{
            $errors[]=$msfErrMsg." no results</div>";
        }
        
        $kwone=$key1;
        $kwtwo=$key2;
        $kwthree=$key3;
        
        $weighted = array();
        
        $linkid = 0;
        $idmax = 0;
        
        foreach($sites_raw as $site_link=>$site){
            try{
                $cntKWone = substr_count(serialize($site),$kwone);
                $cntKWtwo = substr_count(serialize($site),$kwtwo);
                $cntKWthree = substr_count(serialize($site),$kwthree);
                
                if( ($cntKWone == 0) || ($cntKWtwo == 0) || ($cntKWthree == 0) ) {}else{
                    $dataStruct = array($cntKWone,$cntKWtwo,$cntKWthree,$site_link,$links[$linkid],$shorts[$linkid],$title-postwriters[$linkid],$vendor[$linkid]);
                    $weighted[] = $dataStruct;
                    $linkid++;
                    $idmax++;
                }
            }catch (Exception $e){
                $error[]="<div class=\"error\">Some error with conversion</div>";
            }
        }
        
        krsort($weighted);
        $averages=array();
        $index=0;
        
        // foreach($weighted as $arr){
        //     try{
        //     $arithavg = $arr[0]+$arr[1]+$arr[2]/3;
        //     $mult = $arr[0]*$arr[1]*$arr[2];
        //     $sum = $arr[0]+$arr[1]+$arr[2];
        //     $diffone = $arr[0]-$arr[1];
        //     $difftwo = $arr[0]-$arr[2];
        //     $maxfirst = max($arr[0],$arr[1],$arr[2]);
        //     $scale = $maxfirst*$maxfirst;
        //     $metricone = ($diffone*$scale) / ($sum*$scale);
        //     $metrictwo = ($difftwo*$scale) / ($sum*$scale);
        //     $lengthone = $diffone*$diffone;
        //     $lengthwo = $difftwo*$difftwo;
        
        //     if($lengthwo == 0) $lengthwo=1;
        //     $result= ($lengthone / ($lengthone+$lengthwo) ) + $arithavg;
        
        //     $averages[] = array($result,$index);
        //     $index+=1;
        //     }catch(Exception $e){
        //         //be silent like a ninja -_- //>
        //     }
        // }
        // $newweighted = array();
        // foreach($averages as $leitem){
        //         $newweighted[]=$weighted[$leitem[1]];
        // }
        
        // This has to become a view loading with params. Or not. Depends what is better in practice.
        $this->pasdat[]=$weighted;
        echo "<div class=\"jumbotron\">Hello, <strong>not logged user</strong>. This is a result sheet from Prism. Someone gave you this link to validate some claim on the internet.";
        echo "Your friend looked for <strong> ".$str." </strong> and the keywords should be self-explanatory. You agree to cookies.</div>";

        $this->printResults($weighted,$kwone,$kwtwo,$kwthree);
        
        foreach($errors as $error){
            echo $error;
        }
    }

    public function submitctrl(){    
        //Process data here and send them back
        //Require the Transport library to be loaded
        $this->_enableRequests();

        $data = array(
            'keyone_get' => $this->input->get('keyone'),
            'keytwo_get' => $this->input->get('keytwo'),
            'keythree_get' => $this->input->get('keythree'),
            'searchterm_get' => $this->input->get('searchterm'),
            'emailuid' => urlencode($this->input->get('emailuid'))
        );

        if(!strcmp($data['emailuid'],'NULL')){}else{
            $this->uuid=$data['emailuid'];
            $keyword = array(
                'id'=>NULL,
                'uid'=>$this->uuid,
                'termid'=>NULL,
                'keyone'=>$data['keyone_get'],
                'keytwo'=>$data['keytwo_get'],
                'keythree'=>$data['keythree_get'],
                'searchterm'=>$data['searchterm_get'],
                'regdate'=>NULL,
                'cnt'=>NULL
            );
            
            try{
                $this->keyword_model->insert($keyword);
                }catch (Exception $e){
            echo "KW add error";
            }
        }
        
        if( strcmp($data['searchterm_get'],"") && strcmp($data['keyone_get'],"") && strcmp($data['keytwo_get'],"") && strcmp($data['keythree_get'],'') ){ 
            
            Requests::register_autoloader();
            $headers = array('Accept' => 'application/json');
            $goo = Requests::get("https://www.googleapis.com/customsearch/v1?key=AIzaSyBbIBg8XouqPNQ4dFe1cvOF3dMx-0LUi-E&cx=014153420191187252986:u6d2kyoanh0&q=".urlencode($data['searchterm_get'])."&count=1149",$headers)->body;
            $items=json_decode($goo);
            
            $ddg = new Api();
            $ddg->getClient()->addListener(new JsonBodyListener());
            $responsenext = $ddg->api('Disambiguation')->get($data['keyone_get'])->getContent();

            $headers=array('Ocp-Apim-Subscription-Key' => '30bb684dee654c4b97118f89692f69e6',);
            $parameters = array('q'=>urlencode($data['searchterm_get']),
                                'count'=>'1149',
                                'offset'=>'0',
                                'mkt'=>'en-us',
                                'safesearch' => 'Off');
            $responsems = Requests::get("https://api.cognitive.microsoft.com/bing/v5.0/search?q=".$parameters['q']."&count=".$parameters['count']."&offset=".$parameters['offset']."&mkt=".$parameters['mkt']."&safesearch=".$parameters['safesearch'],$headers)->body;
            $itemsms=json_decode($responsems);

            $itemsddg = $responsenext->RelatedTopics;
            $itemsmsf = $itemsms->webPages->value;
            $itemsgoo=array();
            $errors=array();

            $gooErrMsgNo = "<div class=\"error results-list-item\">Google error</div>";

            $itemsgoo = $items->items;

            $sites_raw = array();
            $links = array();
            $shorts = array();
            $titles = array();
            $vendor = array();

            $gooErrMsg = "<div class=\"error results-list-item\">Google API error";
            $ddgErrMsg = "<div class=\"error results-list-item\">DDG API error";
            $msfErrMsg = "<div class=\"error results-list-item\">Bing API error";

            if( count($itemsgoo) > 0){
                foreach($itemsgoo as $item){
                    try{
                        $sites_raw[$item->formattedUrl] = Requests::get($item->link,array(),array('timeout'=>'2.72'))->body;
                        usleep(100);
                        $links[] = $item->link;
                        $shorts[] = $item->htmlSnippet;
                        $titles[] = $item->title;
                        $vendor[] = "goo";
                    }catch(Exception $e){
                        $errors[]=$gooErrMsg." timeout on direct connection</div>"; //DO NEVER CODE LiKE ThiS !!!!! I'm so sorry 
                    }
                }} else{
                $errors[]=$gooErrMsg." no results </div>";
            }

            if(count($itemsddg)>0){
                foreach($itemsddg as $item=>$resutl){
                    if( isset ($resutl->FirstURL) ){
                        $sites_raw[$resutl->FirstURL] = Requests::get($resutl->FirstURL,array(),array('timeout'=>'2.72'))->body;
                        usleep(100);
                        $links[] = (string)$resutl->FirstURL;
                        $shorts[]=(string)$resutl->Result;
                        $titles[]=(string)$resutl->Text;
                        $vendor[]="ddg";
                    }}}else{
                $errors[]= $ddgErrMsg." no results </div>";
            }

            if(count($itemsmsf)>0){
                foreach($itemsmsf as $item=>$resutl){
                    usleep(100);
                    try{
                        $sites_raw[$resutl->displayUrl] = Requests::get($resutl->url,array(),array('timeout'=>'2.72'))->body; //don be an idiot anymore, Boywonder
                        $links[] = $resutl->url;
                        $shorts[] = "MediocreSofts API:".$resutl->name;
                        $titles[] = $resutl->name;
                        $vendor[]="msf";
                    } catch(Exception $e ) {
                        $errors[]=$msfErrMsg." error on direct connection</div>";
                    }}}else{
                $errors[]=$msfErrMsg." no results</div>";
            }

            $kwone=$data['keyone_get'];
            $kwtwo=$data['keytwo_get'];
            $kwthree=$data['keythree_get'];
            $sraczterm=$data['searchterm_get'];

            $weighted = array();

            $linkid = 0;
            $idmax = 0;
            
            foreach($sites_raw as $site_link=>$site){
                try{
                    $cntKWone = substr_count(serialize($site),$kwone);
                    $cntKWtwo = substr_count(serialize($site),$kwtwo);
                    $cntKWthree = substr_count(serialize($site),$kwthree);

                    if( ($cntKWone == 0) || ($cntKWtwo == 0) || ($cntKWthree == 0) ) {}else{
                        $dataStruct = array($cntKWone,$cntKWtwo,$cntKWthree,$site_link,$links[$linkid],$shorts[$linkid],$titles[$linkid],$vendor[$linkid]);
                        $weighted[] = $dataStruct;
                        $linkid++;
                        $idmax++;
                    }
                }catch (Exception $e){
                    $error[]="<div class=\"error\">Some error with conversion</div>";
                }
            }

            krsort($weighted);
            $averages=array();
            $index=0;

            // foreach($weighted as $arr){
            //     try{
            //     $arithavg = $arr[0]+$arr[1]+$arr[2]/3;
            //     $mult = $arr[0]*$arr[1]*$arr[2];
            //     $sum = $arr[0]+$arr[1]+$arr[2];
            //     $diffone = $arr[0]-$arr[1];
            //     $difftwo = $arr[0]-$arr[2];
            //     $maxfirst = max($arr[0],$arr[1],$arr[2]);
            //     $scale = $maxfirst*$maxfirst;
            //     $metricone = ($diffone*$scale) / ($sum*$scale);
            //     $metrictwo = ($difftwo*$scale) / ($sum*$scale);
            //     $lengthone = $diffone*$diffone;
            //     $lengthwo = $difftwo*$difftwo;

            //     if($lengthwo == 0) $lengthwo=1;
            //     $result= ($lengthone / ($lengthone+$lengthwo) ) + $arithavg;
                
            //     $averages[] = array($result,$index);
            //     $index+=1;
            //     }catch(Exception $e){
            //         //be silent like a ninja -_- //>
            //     }
            // }
            // $newweighted = array();
            // foreach($averages as $leitem){
            //         $newweighted[]=$weighted[$leitem[1]];
            // }

            // This has to become a view loading with params. Or not. Depends what is better in practice.
            $this->pasdat[]=$weighted;
            $this->printResults($weighted,$kwone,$kwtwo,$kwthree);

            $remotelink = base64_encode($kwone.'~'.$kwtwo.'~'.$kwthree.'~'.$sraczterm);
            $errors[]= "<div class=\"jumbotron\"><a>".base_url()."Welcome/justquery/".$remotelink."</a></div>";
            
            foreach($errors as $error){
                echo $error;
            }
        }else{
            echo "<div class=\"error results-list-item\"> Bro you need to fill all the fields, ok duh  </div>";
        }
    }

    private function printResults($weighted,$kwone,$kwtwo,$kwthree){
        echo "<div style=\"margin-top:160px\"/>";
        if(count($weighted) == 0){
            echo "<h1>Unfortunately, your search term returned no results that have the keywords you desire. Please re-ask your search term properly or change your keywords</h1><br/>";
        }
        foreach($weighted as $item){
            echo "<div id=\"progressbar\" class=\"jumbotron\" style=\"padding:5px !important;\">";
            echo "<h4><a class=\"outbounda\" href=\"".$item['4']."\" target=\"_blank\">".$item['6']."</a></h4><br/>";
            $red=$green=$blue=0;
            if($item['0']>100){ $red=100; }else{$red=$item['0'];}
            if($item['1']>100){ $green=100; }else{$green=$item['1'];}
            if($item['2']>100){ $blue=100; }else{$blue=$item['2'];}

            echo "<div class=\"progress\" style=\"padding:5px; height:15px; font-size:10px !important; color:rgba(50,50,50) !important;\">";
            echo "<div class=\"progress-bar progress-bar-danger\" style=\"padding:5px; width: ".$red."%\"><p style=\"margin-top:-10px;font-size:13px !important;\"> ".$item['0']."<b>".$kwone."</b> </p></div>";
            echo "</div>";
            
            echo "<div class=\"progress\" style=\"padding:5px; height:15px; font-size:10px !important; color:rgba(50,50,50) !important;\">";
            echo "<div class=\"progress-bar progress-bar-success\" style=\"padding:5px; width: ".$green."%\"><p style=\"margin-top:-10px;font-size:13px !important;\"> ".$item['1']."<b>".$kwtwo."</b> </p></div>";
            echo "</div>";
            
            echo "<div class=\"progress\" style=\"padding:5px; height:15px; font-size:10px !important; color:rgba(50,50,50) !important;\">";
            echo "<div class=\"progress-bar  progress-bar-info\" style=\"padding:5px; width: ".$blue."%\"><p style=\"margin-top:-10px;font-size:13px !important;\">" .$item['2']."<b>".$kwthree."</b> </p></div>";
            echo "</div>";

            echo "<a style=\"padding:5px;\" class=\"ital\">".$item['5']."</a><br/>";
            echo "<a style=\"padding:5px;\" class=\"ital\">".$item['3']."</a><br/>";
            echo "<a style=\"padding:5px;\" class=\"ital\"><img src=\"".base_url('assets/images/'.$item['7'].'.png')."\" style=\"height:33px !important;\">".$item['7']."</img></a><br/></div>";
        }
        
    }

    function _processSite($site){
        
    }
    
    public function who(){
        $this->index();
    }
    public function what(){
        $this->index();
    }
    public function why(){
        $this->index();
    }
    public function account(){
        redirect(base_url("account"));
    }
    
    public function tutorial(){
        $this->styles = array("style");
        $this->menus = array("Auth~Login","Welcome/tutorial~See Tutorial");
        $this->pasdat = array($this->menus);
        $CI = & get_instance();
        $CI->layout = "auth";
        $this->parts[TITLE] = $this->load->view('title',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[CONTENTE] = $this->load->view('tutorial',$this->pasdat,true);
        $this->parts[POPULAR] = $this->load->view('popular',$this->pasdat,true);
        $this->parts[FOOTER] = $this->load->view('header',$this->pasdat,true);
    }

    public function famelist(){
        $this->styles = array("style");
        $this->menus = array("What","Who","Why","Register");
        $this->pasdat = array($this->menus);
        $CI = & get_instance();
        $CI->layout="default_layout";
        $this->parts[TITLE] = $this->load->view('title',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[FAMELIST] = $this->load->view('famelist',$this->pasdat,true);
        $this->parts[FOOTER] = $this->load->view('header',$this->pasdat,true);
    }

    public function notifications(){
        $this->styles = array("style");
        $this->menus = array("What","Who","Why","Register");
        $this->pasdat = array($this->menus);
        $CI = & get_instance();
        $CI->layout="default_layout";
        $this->parts[TITLE] = $this->load->view('title',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts',$this->pasdat,true);
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
        $this->parts[TITLE] = $this->load->view('title',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[NOTIFICATIONSLIST] = $this->load->view('notificationslist',$this->pasdat,true);
        $this->parts[FOOTER] = $this->load->view('header',$this->pasdat,true);
    }

    public function getstarted(){
        $this->styles = array("style");
        $this->menus = array("What","Who","Why","Register");
        $this->pasdat = array($this->menus);
        $this->parts[TITLE] = $this->load->view('title',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[CONTENTE] = $this->load->view('content-getstarted',$this->pasdat,true);
        $this->parts[POPULAR] = $this->load->view('popular',$this->pasdat,true);
        $this->parts[FOOTER] = $this->load->view('header',$this->pasdat,true);

    }

    public function secure($hhash,$redirto='')
    {
        if(!strcmp($redirto,'prism')){
            redirect("Research/secure/".$hhash);
        }
        
        $hash=base64_decode($hhash);
        $secarr=explode('~',$hash);
        $email=$secarr[0];

        $usuario = $this->user_model->get_by('email',$secarr[0]);
        
        $CI = & get_instance();
        $CI->active = password_verify($secarr[2],$secarr[1]);
        $CI->layout="auth";

        $this->styles = array("style");
        $this->menus = array("Slavingway/logoff~Log out now",
                             "Slavingway/secure/".$hhash."/#about~About",
                             "Slavingway/secure/".$hhash."/#contact~Contact",
                             "Account/secure/".$hhash."~Manage Account",
                             "Postwriter/secure/".$hhash."~ PostWriter",
                             "Research/secure/".$hhash."~Research");

        $this->lowermenus = array("#about~About us",
                                  "#contact~Contact us",
                                  "Postwriter~Writing Services",
                                  "Research~Research Tool");

        $this->pasdat = array($this->menus,$this->lowermenus);


        $this->session->set_flashdata('message', array("Hello, <a id=\"emailuid\" name=\"emailuid\" class=\"emailuid\">".$usuario->id."</a> <a>".$email."</a>. You agree to cookies."));
        //TODO:Load data for popular searches
        //TODO:Load data for popular users
        
        $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. By using this service you agree to cookies."));

        $this->parts[TITLE] = $this->load->view('title',$this->pasdat,true);
        $this->parts[HEAD] = $this->load->view('header',$this->pasdat,true);
        $this->parts[SCRIPTS] = $this->load->view('scripts',$this->pasdat,true);
        $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[CONTENTE] = $this->load->view('slavingway',$this->pasdat,true);
        $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
        $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
        $this->parts[FLASH] = $this->load->view('flash',$this->pasdat,true);
        $this->parts[ABOUT] = $this->load->view('about',$this->pasdat,true);
        $this->parts[CONTACT] = $this->load->view('contact',$this->pasdat,true);
    }
    
    public function insertUser(){
        $CI = & get_instance();
        $email=$CI->email;
        
        $user = array(
            'id'=>NULL,
            'email'=>$email,
            'regdate'=>NULL,
            'active'=>false,
            'token'=>NULL
        );

        $result = $this->user_model->insertUser($user);

        if($result != false){
            $refarr = array('success'=>"Not the best of messages.");

            $newuser = $this->user_model->get_by('email',$email);

            $passphrase = $CI->password;
            $uid=$newuser->id;
        
            $password = array(
                'id'=>NULL,
                'uid'=>$uid,
                'secpasswd'=> $passphrase,
                'regdate'=>NULL
            );
        
            $respass = $this->password_model->insertPassword($password);

            if($respass == NULL){
                $refarr = array('success'=>"Password added");
            }
            
            $this->parts[ERROR] = $this->load->view('error',$refarr,true);
        }else{
            $refarr = array('success'=>"Failure, user already exists");
            $this->parts[ERROR] = $this->load->view('error',$refarr,true);
        }
    }
}
