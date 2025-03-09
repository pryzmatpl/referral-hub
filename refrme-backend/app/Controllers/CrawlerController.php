<?php
namespace App\Controllers;
use Exception;
use Nette\Mail\Message;
use Knp\Menu\MenuFactory;
use Respect\Validation\Validator as v;
use Knp\Menu\Renderer\ListRenderer;
use League\OAuth2\Client\Provider\LinkedIn as OauthLI;
use League\OAuth2\Client\Provider\Github as OauthGH;
use App\Models\User;
use App\Models\OA2Clients;
use App\Models\Cart;
use App\Models\Location;
use App\Models\Referral;
use App\Models\Jobdesc;
use App\Models\JobWeight;
use App\Models\Linkedinimport;
use Requests;
use App\Models\Signoff;
use App\Classes\Fitnesscalc;
use App\Classes\Individual;
use Slim\Http\Request;
use Slim\Csrf\Guard;
use Slim\Http\Response;
use Slim\Http\Stream;
use Slim\Http\UploadedFile;
use App\Classes\Population;
use App\Classes\Algorithm;
use Illuminate\Database\Capsule\Manager as DB;
use SlimSession\Helper as Session;

class CrawlerController extends Controller {
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

    public function getlocs($request, $response, $args){
        try{
            $session = new Session;
            $data = Location::all();
            $session['refair-locations'] = $data;
            return json_encode($data);
        }catch(Exception $e){
            return $e;
        }
    }

    public function showlocations($request, $response, $args){
        try{
            $session = new Session;
            $menus = $this->buildmenu();
            $data = Location::all();
            $view = 'locations.vue';

            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'locations'=>$data
                ));

        }catch(Exception $e){
        }
    }

    public function getlocation($request, $response, $args){
        try{
            $session = new Session;
            $menus = $this->buildmenu();
            $data = json_decode(Location::where('id',$args['id'])->get(),true)[0];
            $view = 'location-single.vue';

            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'location'=>$data
                ));

        }catch(Exception $e){
        }
    }

    public function showjobs($request, $response, $args){
        try{
            $session = new Session;
            $menus = $this->buildmenu();
            $data = Jobdesc::all();
            $view = 'jobs.vue';

            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'jobs'=>$data
                ));

        }catch(Exception $e){
        }
    }

    public function showjob($request, $response, $args){
        try{
            $session = new Session;
            $menus = $this->buildmenu();
            $data = Jobdesc::where('id',$args['id'])->get();
            $singlejob = json_decode($data, true)[0];
            $locationdata = Location::where('id', $singlejob['location'])->get();
            $singleloc = json_decode($locationdata, true)[0];
            $view = 'job-new-component-boot.vue';

            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'job'=>$singlejob,
                    'jobid'=>$singlejob['id'],
                    'location'=>$singleloc
                ));

        }catch(Exception $e){
        }
    }

    public function getinterviews($request, $response, $args){
        try{
            $session = new Session;
            $interviews = json_decode(Referral::where('state','OPTED-IN')->get(),true)[0];

            $returnee = [];

            foreach($interviews as $interview){
                $returnee[] = array( 'interview_begin_hour'=>$interview['interview_begin_hour'],
                    'interview_end_hour'=>$interview['interview_end_hour'],
                    'refid' => $interview['id'] ,
                    'interview_date'=>$interview['interview_date']);
            }

            return $response->withHeader('ContentType',
                'application/json')->withJson($returnee);
        }catch(Exception $e){
            return $e;
        }
    }

    public function getreview($request, $response, $args){
        try{
            $menus = $this->buildmenu();
            $view = 'boot-review.vue';
            return $this->view->render($response,
                $view,
                array('menus'=>$menus));
        }catch(Exception $e){
            return $e;
        }
    }

    public function getreviewdata($request, $response, $args){
        $hash = $args['hash'];
        try{
            $session = new Session;
            $data = Referral::where("hash", $hash)->get();
            $arrrefs = json_decode($data, true);

            $singoffs = Signoff::where("hash", $hash)->get();
            $arrsignoffs = json_decode($signoffs, true);

            $menus = $this->buildmenu();
            $view = 'boot-review.vue';

            return $this->view->render($response,
                $view,
                array('menus'=>$menus));
        }catch(Exception $e){
            return $e;
        }
    }


    public function authstate($request, $response, $args){
        try{
            $session = new Session;
            $authst = [ 'logged' => $this->auth->check() ];
            return $response->withHeader(
                'ContentType',
                'application/json')->withJson($authst);
        }catch(Exception $e){
            return $e;
        }
    }

    public function getjobs($request, $response, $args){
        try{
            $session = new Session;
            $data = json_decode(Jobdesc::orderBy('created_at','DESC')->get(),true);
            $session['refair-jobs'] = $data;
            $newjobs = [];
            foreach($data as $jobdesc){
                $jbdscnu = $jobdesc;
                $uniqhash = $jobdesc['location'];
                $alocation = json_decode(Location::where("id",$uniqhash)->get(),true);
                $jbdscnu['locationnew'] = $alocation[0];
                $newjobs[] = $jbdscnu;
            }
            return json_encode($newjobs,true);
        }catch(Exception $e){
            return $e;
        }
    }

    public function getreferrals($request, $response, $args){
        try{
            $session = new Session;
            $data = json_decode(Referral::all(),true);
            $session['refair-refs'] = $data;
            $newrefs = [];
            foreach($data as $jobdesc){
                $jbdscnu = $jobdesc;
                $newrefs[] = $jbdscnu;
            }
            array_reverse($newrefs,false);
            return json_encode($newrefs,true);
        }catch(Exception $e){
            return $e;
        }
    }
    public function getreferral($request, $response, $args){
        try{
            $session = new Session;
            $menus = $this->buildmenu();
            $data = json_decode(Referral::where('id',$args['id'])->get(),true);
            $view='referral-single.twig';
            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'referral'=>$data[0]
                ));
        }catch(Exception $e){
            return $e;
        }
    }

    public function getjob($request, $response, $args){
        try{
            $session = new Session;
            $data = json_decode(Jobdesc::where('id',$args['id'])->get(),true);
            $locdata = json_decode(Location::where('id',$data[0]['location'])->get(),true);
            $session['refair-currjob'] = $data[0];
            $retarray = array('jobdata'=>$data[0],
                'locdata'=>$locdata[0]);

            return json_encode($retarray, true);
        }catch(Exception $e){
            return $e;
        }
    }

    public function getjobkeywords($request, $response, $args){
        try{
            $data = json_decode(Jobdesc::where('id',$args['id'])->get(),true);
            $keywords = explode(',',$data[0]['keywords']); //Strong supposition - only one leme
            return json_encode($keywords);
        }catch(Exception $e){
            return print_r($e);
        }
    }

    public function showreferrals($request, $response, $args){
        try{
            $session = new Session;
            $menus = $this->buildmenu();
            $data = Referral::all();
            $view = 'referrals.vue';

            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'referrals'=>$data
                ));

        }catch(Exception $e){
        }
    }

    public function index($request, $response, $args){
        //TODO : implement this function
        //Get all job descriptions: $dataJobs = $this->jobdesc_model->get_all();
        if( ! $this->auth->check()){
            return $response->withRedirect('/auth/signup');
        }
        //We won't even try if you're not logged in

        try{
            $session = new Session;
            $carts = array();
            $menus = $this->buildmenu();

            $data = Location::all();

            $view = 'jobs.vue';

            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'emailoptions'=>$optsUsers,
                    'carts'=>$stored,
                    'cartid'=>$cartid,
                    'fromindex'=>$fromindex,
                    'locations'=>$data
                ));
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function optin($request, $response, $args){
        $hash = $args['hash'];
        try{
            $session = new Session;
            $menus = $this->buildmenu();
            $rec = Jobdesc::where("hash",$hash)->get();
            $job = json_decode($rec,true)[0];

            $keywords = explode(',',$job['keywords']);
            $session['keywords'] = $keywords;

            $view = 'optin-boot.vue';
            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'job'=>$job,
                    'keywords'>$keywords,
                    'data'=>$arrrefs[0]
                ));
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function bootsignoff($request, $response, $args){
        $jobhash = urlencode($args['jobhash']);
        try{
            //TODO : TEST THIS
            if($request->isXhr()){
                $ajob = json_decode(Jobdesc::where('hash',$jobhash)->get(),true)[0];

                $currentRoute = $request->getAttribute('route');
                $reqIp = $request->getAttribute('ip_address');
                $timeVal = time();

                $sechash = 'prizm';
                $asecure = $this->iwahash($sechash,
                    "IP:".$reqIp."~".
                    "DATE:".$timeVal);
                //TODO: Always urlencode hashes when storing in DB

                $this->session['booted_signoff'] = $asecure;

                $asignoff = Signoff::create([
                    'signup_ip_address'=>$reqIp,
                    'referred_id'=>$reqIp,
                    'referrer_id'=>'BOOT',
                    'reviewers_hash'=>$asecure,
                    'name'=>'ANONYMOUSSIGNOFF',
                    'statehash'=>urlencode($asecure),
                    'token'=>$asecure,
                    'jobid'=>$ajob['id'],
                    'cvfile'=>'EMPTY'
                ]);
                $asignoff->save();

                return $response->withJson(array('signoffboot'=>true,
                    'ip'=>$reqIp,
                    'hash'=>$asecure));
            }else{
                return "THIS IS ONLY POSSIBLE FROM REACTIVE UI";
            }
        }catch(Exception $e){
            return print_r($e);
        }
    }

    public function unpauseignoff($request, $response, $args){
        try{
            //TODO : TEST THIS
            if($request->isXhr()){
                $currentRoute = $request->getAttribute('route');
                $reqIp = $request->getAttribute('ip_address');
                $timeVal = time();
                $session = new Session;
                $session['booted_signoff'] = $args['signoffhash'];

            }else{
                return "THIS IS ONLY POSSIBLE FROM REACTIVE UI";
            }
        }catch(Exception $e){
            return print_r($e);
        }
    }


    public function signoffdata($request, $response, $args){
        $hash = $args['hash'];
        try{
            $data = Referral::where('hash',$hash)->get();
            $referral = json_decode($data, true)[0];

            $jbid = $referral['jobid'];
            $rec = Jobdesc::where("id",$jbid)->get();

            $job = json_decode($rec,true)[0];
            $locid = $job['location'];

            $loc = Location::where("id",$locid)->get();
            $locdat = json_decode($loc,true)[0];

            $sechash = 'prizm';
            $this->iwahash($sechash,"REFHASH:".$referral['hash']);
            $this->iwahash($sechash,"LOCID:".$locid);
            $this->iwahash($sechash,"JOBID:".$job['id']);

            $returnee[] = array('referral'=>$referral,
                'location'=>$locdat,
                'job'=>$job,
                'securehash' => urlencode($sechash));

            return $response->withHeader('ContentType',
                'application/json')->withJson($returnee);

        }catch(Exception $e){
            print_r($e);
        }
    }

    public function registerpostoptindata($request, $response, $args){
        try{
            $postData = $request->getParsedBody();

            $jobhash = $args['jobhash']; //Job for which this review is bing done
            $signoffhash = urlencode($args['signoffhash']);

            $modref = json_decode(Signoff::where('statehash', $signoffhash)->get(),true)[0];
            $jobe = json_decode(Jobdesc::where('hash',$jobhash)->get(),true)[0];

            $validation = $this->validator->validate($request,[
                'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
                'password' => v::noWhitespace()->notEmpty()
            ]);


            $burl = env('BASE_URL');

            //Validate password and email
            if( $validation->failed() ) {
                $this->flash->addMessage('error','Validation of your data failed - your email is taken or you supplied bad strings. Please retry.');
                return $burl.'/optin/'.$jobhash.'/'.$signoffhash;
            }

            //Randomize Name
            $newname = $request->getParam('email');
            $aname = explode('@',$newname)[0];

            //Creating new user
            $activCode = md5($signoffhash . date('Ymdhis'));
            $user = User::create([
                'email' => $request->getParam('email'),
                'name' => $aname.'~'.$signoffhash,
                'password' => password_hash($request->getParam('password'),PASSWORD_DEFAULT),
                'activ_code' => $activCode, // <-- add the activation code to database
                'latest_signoff' => $signoffhash,
                'group_id' => $request->getParam('chosengroup')
            ]);

            //Activating user for redirect
            $user->activ = 1;
            $user->update(['activ'=>1]);

            //Logging the user in
            $auth = $this->auth->attempt(
                $request->getParam('email'),
                $request->getParam('password')
            );

            $mail = new Message;
            $mail->setFrom(env('MAIL_USERNAME'))
                ->addTo($request->getParam('email'))
                ->setSubject('Refair.me Registration and Opt-In for a position monit.')
                ->setHTMLBody("<h2>Hi, thanks for starting your Opt-In for ".$jobe['jobtitle'].". <hr/>  ".
                    "We have some news. You opted in for job #".$modref['jobid']."</br> and joined refair.me for future job offerings.".
                    "<br/><h3>Click this URL first, please:</b><br/>".
                    "<strong><a href=".$burl."/auth/confirm?code=".$activCode.">Activate your Refair.me</a><br/>".
                    "<hr/>The status of your signoff is now OPTED-IN, and the recruiter closes it upon a sucesfull hire. Please click the link below to see your code challenge:<br/>".
                    "<a href=".$burl."/optin/".urlencode($jobhash)."/".urlencode($signoffhash).">Take me to my Challenge!</a><br/>".
                    "<hr/>Have a good day and we wish you luck!");
            $this->mailer->send($mail);

            $this->flash->addMessage('info','Sucesfull signup and opt-in! Welcome to your code challenge!');

            return $response->withJson(['addr'=>$burl.'/optin/'.urlencode($jobhash).'/'.urlencode($signoffhash)]);

        }catch(Exception $e){
            print_r($e);
        }
    }

    public function optindata($request, $response, $args){
        $hash = $args['hash'];
        try{
            $rec = Jobdesc::where('hash', urlencode($hash))->get() or die('failed');
            //TODO: REMEMBER TO URLENCODE BEFORE PASSSING HASH SEARCHES!!!!!!
            $job = json_decode($rec,true)[0];
            $locid = $job['location'];
            $loc = Location::where('id', $locid)->get();
            $locdat = json_decode($loc,true)[0];
            $ref = Referral::where('jobid', $job['id'])->orderBy('created_at','DESC')->get();
            $arrefs = json_decode($ref,true);
            //We leave signoffs as means to say that more referrals are further than him
            $soffs = Signoff::where('jobid', $job['id'])->orderBy('created_at','DESC')->get();
            $arrsofs = json_decode($soffs,true);

            return $response->withHeader(
                'ContentType',
                'application/json')->withJson(array('jobdesc'=>$job,
                'location'=>$loc,
                'referral'=>$arrefs,
                'signoffs'=>$arrsofs));

        }catch(Exception $e){
            print_r($e);
        }
    }



    public function getoptedindata($request, $response, $args){
        $jobhash = $args['hash'];
        $signoffhash = $args['signoffhash'];
        try{
            $rec = Jobdesc::where('hash', urlencode($jobhash))->get();
            //TODO: REMEMBER TO URLENCODE BEFORE PASSSING HASH SEARCHES!!!!!!
            $job = json_decode($rec,true)[0];
            $locid = $job['location'];
            $loc = Location::where('id', $locid)->get();
            $locdat = json_decode($loc,true)[0];
            $ref = Referral::where('jobid', $job['id'])->orderBy('created_at','DESC')->get();
            $arrefs = json_decode($ref,true);
            $soffs = Signoff::where('statehash', urlencode($signoffhash))->get();
            $arrsofs = json_decode($soffs,true);

            return $response->withHeader(
                'ContentType',
                'application/json')->withJson(array('jobdesc'=>$job,
                'location'=>$loc,
                'referral'=>$arrefs,
                'signoff'=>$arrsofs));


        }catch(Exception $e){
            print_r($e);
        }
    }

    public function getopted($request, $response, $args){
        $jobhash = $args['hash'];
        $signoffhash = $args['signoffhash'];
        $menus = $this->buildmenu();

        try{
            $rec = Jobdesc::where('hash', urlencode($hash))->get() or die('failed');
            //TODO: REMEMBER TO URLENCODE BEFORE PASSSING HASH SEARCHES!!!!!!
            $job = json_decode($rec,true)[0];
            $locid = $job['location'];
            $loc = Location::where('id', $locid)->get();
            $locdat = json_decode($loc,true)[0];
            $ref = Referral::where('jobid', $job['id'])->orderBy('created_at','DESC')->get();
            $arrefs = json_decode($ref,true);
            $soffs = Signoff::where('statehash', urlencode($signoffhash))->get();
            $arrsofs = json_decode($soffs,true);

            $view = 'optin-boot.vue';
            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'job'=>$job,
                    'keywords'>$keywords,
                    'data'=>$arrrefs[0]
                ));

        }catch(Exception $e){
            print_r($e);
        }
    }

    function moveUploadedFile($directory, UploadedFile $uploadedFile, $signoffsuffix)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s',
            $basename.'~'.$signoffsuffix,
            $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    public function postreviewfile($request, $response, $args){
        try{
            //TODO: Asserted that this comes after you enter the review window only
            //TODO:TEST
            $directory = '/usr/share/nginx/refair/storage/cvs';
            $uploadedFiles = $request->getUploadedFiles();

            $burl = env('BASE_URL');

            $session = new Session;
            if( $session['booted_signoff'] == undefined ){
                throw new Exception("Our server does not match your signoff Hash. What are you doing - using the stack without a UI? : h1# ".$postedHashh.", h2# ".$session['booted_signoff'] );
            }else{
                $booted = $session['booted_signoff'];
                $as = Signoff::where('statehash', $booted)->get();

                $signoff = json_decode($as,true)[0]; //Get signoffhash
                if($signoff == undefined){
                    throw new Exception("Signoff is undefined in session. Are you trying to hack us?");
                }else{

                    // handle single input with single file upload
                    // from slim docs
                    $uploadedFile = $uploadedFiles['file'];
                    if($uploadedFile->getError() === UPLOAD_ERR_OK) {
                        $filename = $this->moveUploadedFile($directory,
                            $uploadedFile,
                            $session['booted_signoff']);

                        $signoff['cvfile'] = $filename;

                        if($this->auth->check()){
                            $signoff['name'] = $this->auth->user['email'];
                        }else{
                            $signoff['name'] = "ANONYMOUS";
                        }

                        $updSignoff = json_decode(Signoff::where('statehash',
                            $session['booted_signoff'])->get(), true)[0];

                        $jobhash = $request->getParam('secureHash');
                        $jobe = json_decode(Jobdesc::where('hash', $jobhash)->get(),true)[0];

                        $poster_id = $jobe['poster_id'];
                        $poster = $request->getParam('poster_id');

                        $signoff['statehash'] = $updSignoff->statehash;

                        $updSignoff->cvfile = $signoff['cvfile'];
                        $updSignoff->name = $signoff['name'];


                        $updse = Signoff::where('id',$signoff['id']);
                        $updse->id=$signoff['id'];
                        $updse->name="CV_UPLOAD";
                        $updse->cvfile=$filename;
                        $updse->update(['id'=>$signoff['id'],
                            'name'=>$signoff['name'],
                            'cvfile'=>$filename
                        ]);

                        $mail = new Message;

                        $mail->setFrom(env('MAIL_USERNAME'))
                            ->addTo($poster)
                            ->addTo($poster_id)
                            ->setSubject('Refair.me alert - someone uploaded a CV for your job offer.')
                            ->setHTMLBody("Hello, this email confirms that a CV landed at your job offer. ".
                                "It is now tied to an active recruting process. You will find it under your 'Review' tab of the active process.<br/><hr/>".
                                "<h3>It is available here: <a href=\"".$burl."/cvaccess/".$filename."\"> check out the new CV</a>.</h3><br/><hr>".
                                "Have a good day!");
                        $this->mailer->send($mail);

                        $response->write('Uploaded ' . $filename . '<br/>');

                    }

                    return $response->withJson("Sucess!");
                }
            }
        }catch(PDOException $e){
            return $e;
        }
    }

    public function postcodetar($request, $response, $args){
        try{
            //TODO: Asserted that this comes after you enter the review window only
            //TODO:TEST
            $directory = '/usr/share/nginx/refair/storage/code';
            $uploadedFiles = $request->getUploadedFiles();

            $session = new Session;
            if( $session['booted_signoff'] == undefined ){
                throw new Exception("Our server does not match your signoff Hash. What are you doing - using the stack without a UI? : h1# ".$postedHashh.", h2# ".$session['booted_signoff'] );
            }else{
                $signoff = json_decode(Signoff::where('statehash',
                    $session['booted_signoff'])->get(),true)[0]; //Get signoffhash
                if($signoff == undefined){
                    throw new Exception("Signoff is undefined in session. Are you trying to hack us?");
                }else{

                    // handle single input with single file upload
                    // from slim docs
                    $uploadedFile = $uploadedFiles['file'];
                    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                        $filename = $this->moveUploadedFile($directory,
                            $uploadedFile,
                            $session['booted_signoff']);

                        $signoff['cvfile'] = $filename;
                        $signoff['name'] = "CODEUPLOADED~";

                        $updSignoff = Signoff::where('statehash',
                            $session['booted_signoff']) or die("Coulnd't get statehash");

                        $signoff['reviewers_hash'] = $updSignoff->statehash;

                        $updSignoff->cvfile = $signoff['cvfile'];
                        $updSignoff->name = $signoff['name'];

                        $updSignoff->update(['id'=>$signoff['id'],
                            'name'=>$signoff['name'],
                            'cvfile'=>$signoff['cvfile']
                        ]) or die("A:".$signoff['cvfile']." "."B:".$signoff['name']." "."C:".$signoff['statehash']);

                        $response->write('Uploaded ' . $filename . '<br/>');
                    }

                    return $response->withJson("Sucess!");
                }
            }

        }catch(Exception $e){
            return $e;
        }
    }

    public function savechallenge($request,$response,$args){
        $jobhash = $args['hash'];
        $signoffhash = $args['signoffhash'];
        try{
            $userid = $request->getParam('userid');
            $jobid = $request->getParam('jobid');
            $poster = $request->getParam('posterid');
            $signoffid = $request->getParam('signoff');
            $codeval = $request->getParam('codeval');
        }catch(Exception $e){
            return $e;
        }
    }

    public function csrftoken($request, $response, $args){
        $slimGuard = new Guard;
        //    $slimGuard->validateStorage();
        // Generate new tokens
        $csrfNameKey = $slimGuard->getTokenNameKey();
        $csrfValueKey = $slimGuard->getTokenValueKey();
        $keyPair = $slimGuard->generateToken();
        return $response->withJson($keyPair);
    }

    public function postoptindata($request, $response, $args){
        $hash = $args['hash'];
        try{
            $modref = new Referral;
            $postData = $request->getParsedBody();

            $dayitself = explode('T', $postData['day'])[0];
            $newref->interview_begin_hour = $dayitself.' '.$postData['beginhour']['HH'].':'.$postData['beginhour']['mm'];
            $modref->interview_end_hour = $dayitself.' '.$postData['endhour']['HH'].':'.$postData['endhour']['mm'];
            $modref->interview_date = $dayitself;
            $modref->state = "OPTED-IN";


            $modref->save(array('interview_begin_hour'=> $dayitself.' '.$postData['beginhour']['HH'].':'.$postData['beginhour']['mm'],
                    'interview_end_hour' => $dayitself.' '.$postData['endhour']['HH'].':'.$postData['endhour']['mm'],
                    'interview_date' => $dayitself,
                    'state'=> "OPTED-IN")
            );

            $burl=env('BASE_URL');
            $necdata =  Referral::where("hash", $hash)->get();
            $referrer= $postData['referrer'];
            $referred = $postData['referred'];
            $poster = $postData['posterid'];

            $mail = new Message;
            $mail->setFrom(env('MAIL_USERNAME'))
                ->addTo($referrer)
                ->addTo($referred)
                ->addTo($poster)
                ->setSubject('Refair.me alert - thank you for opting in! A confirmation note.')
                ->setHTMLBody("Hello, this email confirms optin of ".$postData['referred']." for this <a href=\"".$burl."/show/job/".$jobid.
                    "\">position</a>. This note reminds you of the interview on ".$dayitself.
                    ", between ".$postData['beginhour']['HH'].':'.$postData['beginhour']['mm']." and ".
                    $postData['endhour']['HH'].':'.$postData['endhour']['mm'].
                    ". Your referral is now OPTED-IN, and the recruiter closes it upon a sucesfull hire.".
                    ". Have a good day!");
            $this->mailer->send($mail);

            $this->flash->addMessage('error','Thank you for opting-in!');

            return $this->router->pathFor('home');
        }catch(Exception $e){
            return $e;
        }
    }

    public function postrefairoptindata($request, $response, $args){
        try{
            //We are getting the JOBID hash
            $jobhash = urlencode($args['hash']);
            $burl=env('BASE_URL');

            $postData = $request->getParsedBody();
            $candidate = $postData['candidate'];

            $job = json_decode(Jobdesc::where('hash',$jobhash)->get(),true)[0];
            $jobid = $job['id'];
            $poster = $job['poster_id'];
            $signoffhash = urlencode($request->getParam('signoffhash'));

            $soff = json_decode(Signoff::where('statehash',$signoffhash)->get(),true)[0];

            $soff->jobid = $job['id'];
            $soff->cvfile = $signoffhash;

            $soff->reviewers_hash = 'OPTED-IN';

            $mail = new Message;
            $mail->setFrom(env('MAIL_USERNAME'))
                ->addTo($candidate)
                ->addTo($poster)
                ->setSubject('Refair.me alert - thank you for opting in! Here are your next steps...')
                ->setHTMLBody("Hello, this email confirms optin of ".$candidate." for this <a href=\"".$burl."/show/job/".$jobid.
                    "\">position</a>. The Code Challenge is available at the review window from now on ".
                    "<a href =\"".$burl."/codechallenge/".$job['hash']."/".$signoffhash."\" > This is the link to your next step of the recruitment process </a>".
                    ". Have a good day!");
            $this->mailer->send($mail);

            $this->flash->addMessage('info','Thank you for opting-in! Your code challenge is available.');

            return $burl.'/optin/'.$jobhash.'/'.$signoffhash;
        }catch(Exception $e){
            return $e;
        }
    }

    public function loginpostoptindata($request, $response, $args){
        $hash = $args['hash'];
        try{
            $modref = Referral::where("hash", $hash);
            $postData = $request->getParsedBody();

            $auth = $this->auth->attempt(
                $request->getParam('email'),
                $request->getParam('password')
            );

            if (!$auth) {
                $this->flash->addMessage('error','Could not sign you in with those details');
                return $this->router->pathFor('auth.signin');
            }

            $dayitself = explode('T', $postData['day'])[0];
            $newref->interview_begin_hour = $dayitself.' '.$postData['beginhour']['HH'].':'.$postData['beginhour']['mm'];
            $modref->interview_end_hour = $dayitself.' '.$postData['endhour']['HH'].':'.$postData['endhour']['mm'];
            $modref->interview_date = $dayitself;
            $modref->state = "OPTED-IN";

            $modref->update(array('interview_begin_hour'=> $dayitself.' '.$postData['beginhour']['HH'].':'.$postData['beginhour']['mm'],
                    'interview_end_hour' => $dayitself.' '.$postData['endhour']['HH'].':'.$postData['endhour']['mm'],
                    'interview_date' => $dayitself,
                    'state'=> "OPTED-IN")
            );

            $this->flash->addMessage('error','Thanks for logging in and opting in!');
            return "/";
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function loginpostoptin($request, $response, $args){
        try{
            $hash = $args['hash'];
            $modref = Referral::where('hash', $hash);
            $postData = $request->getParsedBody();

            $modref = Signoff::where('statehash', $hash);
            $modref->name=$request->getParam('email');
            $modref->update(['name'=>$request->getParam('email'),
                'referrer_id'=>'OPTED-IN'
            ]);

            $jobref = Jobdesc::where('hash', $hash);
            $signoffhash = $postData['cvfilehash'];
            $jobHash = $hash;

            $auth = $this->auth->attempt(
                $request->getParam('email'),
                $request->getParam('password')
            );

            if (!$auth) {
                $this->flash->addMessage('error','Could not sign you in with those details');
                return $this->router->pathFor('auth.signin');
            }

            $this->flash->addMessage('info','Thanks for logging in and opting in!');

            return $burl.'/optin/'.$hash;
        }catch(Exception $e){
            print_r($e);
        }
    }


    public function registerpostreviewdata($request, $response, $args){
        $hash = $args['hash'];
        try{
            $modref = Referral::where("hash", $hash);
            $postData = $request->getParsedBody();


            $dayitself = explode('T', $postData['day'])[0];
            $modref->interview_begin_hour = $dayitself.' '.$postData['beginhour']['HH'].':'.$postData['beginhour']['mm'];
            $modref->interview_end_hour = $dayitself.' '.$postData['endhour']['HH'].':'.$postData['endhour']['mm'];
            $modref->interview_date = $dayitself;
            $modref->state = "OPTED-IN";

            $modref->update(array('interview_begin_hour'=>$dayitself.' '.$postData['beginhour']['HH'].':'.$postData['beginhour']['mm'],
                    'interview_end_hour' => $dayitself.' '.$postData['endhour']['HH'].':'.$postData['endhour']['mm'],
                    'interview_date' => $dayitself,
                    'state'=> "OPTED-IN")
            );

            $validation = $this->validator->validate($request,[
                'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
                'password' => v::noWhitespace()->notEmpty()
            ]);

            if ($validation->failed()) {
                $this->flash->addMessage('error','Validation of your data failed. Please click on the link again.');
                return $this->router->pathFor('auth.signup');
            }

            $activCode = md5('yourSalt' . date('Ymdhis'));

            $newname = $request->getParam('email');
            $aname = explode('@',$newname)[0];

            $user = User::create([
                'email' => $request->getParam('email'),
                'name' => $aname.'xxrefairhotshot',
                'password' => password_hash($request->getParam('password'),PASSWORD_DEFAULT),
                'activ_code' => $activCode, // <-- add the activation code to database
                'group_id' => $request->getParam('chosengroup')
            ]);

            $burl=env('BASE_URL');

            $mail = new Message;
            $mail->setFrom(env('MAIL_USERNAME'))
                ->addTo($request->getParam('email'))
                ->setSubject('Thank you for optin in and registering! Please confirm - from Refair.me')
                ->setHTMLBody("Hello, this email confirms you attempted to register with refair.me.<br/> It also means ".
                    "that you opted in on job offer ".$modref->jobid.", referred by ".$modref->referred_id.
                    "Click this URL: <br />".
                    "<a href=".$burl."/auth/confirm?code=".$activCode.">".
                    $burl."/auth/confirm?code=".$activCode."</a><br/>You are registered as ".
                    $request->getParam('chosengroup').
                    " . This note also reminds you of the interview on ".$dayitself.
                    ", between ".$postData['beginhour']['HH'].':'.$postData['beginhour']['mm']." and ".
                    $postData['endhour']['HH'].':'.$postData['endhour']['mm'].
                    ". Your referral is now OPTED-IN, and the recruiter closes it upon a sucesfull hire.".
                    ". Have a good day!");
            $this->mailer->send($mail);

            $this->flash->addMessage('info','Sucesfull signup and opt-in! Have a great day.');

            return $this->router->pathFor('auth.signin');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function sessionkeywords($request, $response, $args){
        try{
            $session = new Session;
            $kews = $session['keywords'];
            $retstr = implode(',',$kews);
            return $retstr;
        }catch(Exception $e){
            return $e;
        }
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

    public function searchjobs($request, $resonse){
        try{
            $queryAll = $request->getQueryParams();

            $allJobs = json_decode(JobWeight::all(),true);

            $retarr = ["results"=>[]];

            $query = urldecode($queryAll['q']);
            $command = 'sudo /usr/share/nginx/refair/resources/pythonapis/match/API/run.py '.$query;
            $returned = $this->my_shell_exec($command,$retun,$rettwo);
            $weights = json_decode($retun,true)['predictions']; //get array of predictions

            $retarr["weights"] = $weights;

            foreach($allJobs as $job){
                $points = 0;

                if( (weights[0] <= ($job->aone+0.3)) && (weights[0] > ($job->aone-0.3))) $points++;
                if( (weights[1] <= ($job->atwo+0.3)) && (weights[1] > ($job->atwo-0.3))) $points++;
                if( (weights[2] <= ($job->athree+0.3)) && (weights[2] > ($job->athree-0.3))) $points++;
                if( (weights[3] <= ($job->afour+0.3)) && (weights[3] > ($job->afour-0.3))) $points++;
                if( (weights[4] <= ($job->afive+0.3)) && (weights[4] > ($job->afive-0.3))) $points++;
                if( (weights[5] <= ($job->asix+0.3)) && (weights[5] > ($job->asix-0.3))) $points++;
                if( (weights[6] <= ($job->aseven+0.3)) && (weights[6] > ($job->aseven-0.3))) $points++;
                if( (weights[7] <= ($job->aeight+0.3)) && (weights[7] > ($job->aeight-0.3))) $points++;
                if( (weights[8] <= ($job->anine+0.3)) && (weights[8] > ($job->anine-0.3))) $points++;
                if( (weights[9] <= ($job->aten+0.3)) && (weights[9] > ($job->aten-0.3))) $points++;
                if( (weights[10] <= ($job->aeleven+0.3)) && (weights[10] > ($job->aeleven-0.3))) $points++;

                if($points >= 3){
                    $jobid = $job['jobid'];
                    $loadjob = json_decode(Jobdesc::where('id',$jobid)->get(),true)[0];
                    if($loadjob['jobtitle']==''){
                        $retarr["results"][] = ['name'=>"Hello",
                            'text' => "Weight exists, but the job does probably not. DB Integrity errors. Points:".$points];
                    }else{
                        $retarr["results"][] = ['name' => $loadjob['jobtitle'],
                            'text' => $loadjob['essentials'],
                            'jobid'=> $loadjob['id']
                        ];
                    }
                }
            }

            return json_encode($retarr,true);

        }catch(Exception $e){
            $this->session->set_flashdata('error',"Something went wrong");
        }
    }

    public function evalkeywords($request, $response,$args){
        try{
            $queryAll = $request->getQueryParams();
            $query = urldecode($queryAll['eval']);
            $retarr = ["results"=>[json_encode($query,true)]];

            $command = 'sudo /usr/share/nginx/refair/resources/pythonapis/match/API/run.py '.$query;
            $returned = $this->my_shell_exec($command,$retun,$rettwo);
            $weights = json_decode($retun,true)['predictions']; //get array of predictions
            $retarr["weights"] = $weights;
            return $response->withJson($retarr);
        }catch(Exception $e){
            $this->session->set_flashdata('error',"Something went wrong");
        }
    }

    public function evaljob($request, $response,$args){
        try{
            $jobid = $args['jobid'];
            $jobweights = json_decode(JobWeight::where('jobid',$jobid)->get(),true)[0];
            $retarr = ["results"=>["Result from job eval during job posting"]];
            $retarr["weights"][] = $jobweights['aone'];
            $retarr["weights"][] = $jobweights['atwo'];
            $retarr["weights"][] = $jobweights['athree'];
            $retarr["weights"][] = $jobweights['afour'];
            $retarr["weights"][] = $jobweights['afive'];
            $retarr["weights"][] = $jobweights['asix'];
            $retarr["weights"][] = $jobweights['aseven'];
            $retarr["weights"][] = $jobweights['aeight'];
            $retarr["weights"][] = $jobweights['anine'];
            $retarr["weights"][] = $jobweights['aten'];
            $retarr["weights"][] = $jobweights['aeleven'];
            return $response->withJson($retarr);
        }catch(Exception $e){
            $this->session->set_flashdata('error',"Something went wrong");
        }
    }

    public function newpagination(){
        echo "<p>".$this->jobdesc_model->all_pages."</p>";
        echo "<p>".$this->jobdesc_model->previous_page."</p>";
        echo "<p>".$this->jobdesc_model->next_page."</p>";
    }

    public function addjob($request, $response, $args){
        try{
            $session = new Session;
            $menus = $this->buildmenu();
            $data = Jobdesc::where('id',$args['id'])->get();
            $jobdesc = json_decode($data,true)[0];

            $view = 'job-add-boot.vue';

            return $this->view->render($response,
                $view,
                array('menus'=>$menus ,
                    'jobdesc'=>$data
                ));
        }catch(Exception $e){
            return $e;
        }
    }

    public function postlocation($request, $response, $args){
        try{
            $session = new Session;
            $data = new Location;

            //This is an endpoint for async adding of locations
            $postData = $request->getParsedBody();

            $data['name'] = strip_tags($postData['name']);
            $data['city'] = strip_tags($postData['city']);
            $data['country'] = strip_tags($postData['country']);
            $data['address'] = strip_tags($postData['address']);
            $data['zip'] = strip_tags($postData['zip']);
            $data['lat'] = strip_tags($postData['latitude']);
            $data['lng'] = strip_tags($postData['longitude']);
            $data['currency'] = strip_tags($postData['currency']);
            $data['description'] = nl2br(strip_tags($postData['description'])); //TODO: added nl2br for location description
            $data['regdate'] = date("Y-m-d H:i:s",time());
            $dat='prizm';
            $data['hash'] = urlencode($this->iwahash($dat,
                $this->iwahash($dat,
                    "NAME:".$postData['name']."~".
                    "CITY:".$postData['city']."~".
                    "ZIP:".$postData['zip'])));
            $data->save();

            $burl = env('BASE_URL'); //TODO: added base_url to postlocation
            return $burl."/locations/".$data['id'];
        }catch(Exception $e){
            return $e;
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

    public function postreferral($request, $response, $args){
        try{
            $nudata = $request->getParsedBody();

            $ref = new Referral;

            $ref['referred_id'] = $nudata['referred'];
            $ref['referrer_id'] = $nudata['referrer'];
            $ref['name'] = $nudata['role'];
            $ref['keywords'] = $nudata['keywords'];
            $ref['state'] = "FIRST SHOT";
            $ref['jobid'] = $nudata['jobid'];
            $ref['location_id'] = $nudata['locationid'];
            $ref['regdate'] = date("Y-m-d H:i:s",time());
            $dat="prizm";
            $ref['hash'] = urlencode($this->iwahash($dat,
                "UNIQREF:".
                $this->iwahash($dat,
                    "JOBID:".$nudata['jobid'].
                    "~LOCID:".$nudatap['locationid'].
                    "~KEYWORDS:".$nudata['keywords'].
                    $ref['regdate'].
                    "~STATE:FIRST SHOT"))
            );

            $ref->save();

            $job = json_decode(Jobdesc::where('id',$ref['jobid'])->get(), true)[0];
            $senthash = $job['hash'];

            $burl = env('BASE_URL');

            $mail = new Message;
            $mail->setFrom(env('MAIL_USERNAME'))
                ->addTo($nudata['referred'])
                ->addTo($nudata['referrer'])
                ->setSubject('Hello from Refair.me! '.$nudata['referred'].' has been referred for a position by '
                    .$nudata['referrer'])
                ->setHTMLBody("Hello, this email confirms a started recruitment flow at refair.me.<br/>  <br /><a href=\"".$burl."/optin/".$senthash."\">Click this URL to see the opt-in window</a><br/>You should see a new referral if you are a registered user.");
            $this->mailer->send($mail);

            $mail = new Message;
            $mail->setFrom(env('MAIL_USERNAME'))
                ->addTo($nudata['posterid'])
                ->setSubject('Hello from Refair.me! '.$nudata['referred'].' has been referred for a position by '.$nudata['referrer'])
                ->setHTMLBody("We have news on your job post! This message confirms a started recruitment flow at refair.me.<br/>  <br /><a href=\"".$burl."/optin/".$senthash."\">Click this URL to see the review window. This window can be shared by you, with the referred person, whenever you're ready to push the hiring process.</a><br/>. If the email of the referred person is the same as that of the referrer, said person applied for your job. Happy interviewing!");
            $this->mailer->send($mail);


            $this->flash->addMessage('info','Thank you for using refair.me.');
            $burl = env('BASE_URL');
            return $burl."/referrals/".$ref['id'];
        }catch (PDOException $e){
            return json_encode($e);
        }
    }

    function my_shell_exec($cmd, &$stdout=null, &$stderr=null) {
        $proc = proc_open($cmd,[
            1 => ['pipe','w'],
            2 => ['pipe','w'],
        ],$pipes);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        return proc_close($proc);
    }

    function is_exec_available() {
        static $available;

        if (!isset($available)) {
            $available = true;
            if (ini_get('safe_mode')) {
                $available = false;
            } else {
                $d = ini_get('disable_functions');
                $s = ini_get('suhosin.executor.func.blacklist');
                if ("$d$s") {
                    $array = preg_split('/,\s*/', "$d,$s");
                    if (in_array('exec', $array)) {
                        $available = false;
                    }
                }
            }
        }

        return $available;
    }


    public function postjob($request, $response, $args){
        try{
            $nudata = $request->getParsedBody();

            $ref = new Jobdesc;

            $ref['jobtitle'] = strip_tags($nudata['jobtitle']);
            $ref['required_exp'] = strip_tags($nudata['requiredexp']);
            $ref['required_fund'] = strip_tags($nudata['requiredfund']);
            $ref['required_relocation'] = strip_tags($nudata['requiredrelocation']);
            $ref['required_remote'] = strip_tags($nudata['required_remote']);
            $ref['regdate'] = date("Y-m-d H:i:s",time());
            $ref['keywords'] = strip_tags($nudata['keywords']);
            $ref['musthave'] = strip_tags($nudata['musthave']);
            $ref['nicetohave'] = strip_tags($nudata['nicetohave']);
            $ref['essentials'] = strip_tags($nudata['essentials']);
            $ref['specs'] = strip_tags($nudata['specs']);
            $ref['other'] = strip_tags($nudata['other']);
            $ref['location'] = strip_tags($nudata['location']);
            $ref['description'] = strip_tags(nl2br($nudata['jobdesc']), '<p><br>');
            //TODO: remeber to add validation to description! This is wide open for mysql hacks
            $ref['poster_id'] = strip_tags($nudata['posterid']);
            $ref['bounty'] = strip_tags($nudata['bounty']);
            $dat='prizm';
            $nuhash = urlencode($this->iwahash($dat,
                "JOBID:".
                $this->iwahash($dat,
                    "JOBTITLE:".$nudata['jobtitle'].
                    "~LOCID:".$nudatap['locationid'].
                    "~KEYWORDS:".$nudata['keywords'].
                    "~POSTERID:".$nudata['posterid'].
                    "~REGDATE:".$ref['regdate'])));
            $ref['hash']  = $nuhash;
            $ref->save();



            //Add new job weight
            //TODO: Refator this into function {
            $weight = new JobWeight;
            $weighingKeywords = $ref['essentials']; //take the must have keywords
            $command = 'sudo /usr/share/nginx/refair/resources/pythonapis/match/API/run.py '.$weighingKeywords;
            $returned = $this->my_shell_exec($command,$retun,$rettwo);
            $weights = json_decode($retun,true)['predictions']; //get array of predictions

            $weight['aone'] = $weights[0];
            $weight['atwo'] = $weights[1];
            $weight['athree'] = $weights[2];
            $weight['afour'] = $weights[3];
            $weight['afive'] = $weights[4];
            $weight['asix'] = $weights[5];
            $weight['aseven'] = $weights[6];
            $weight['aeight'] = $weights[7];
            $weight['anine'] = $weights[8];
            $weight['aten'] = $weights[9];
            $weight['aeleven'] = $weights[10];
            $weight['jobid'] = $ref['id'];
            $weight->save();
            //}

            //TODO: Refactor this into function {
            $mail = new Message;
            $burl=env('BASE_URL');
            $mail->setFrom(env('MAIL_USERNAME'))
                ->addTo($nudata['posterid'])
                ->setSubject("Refair message - a new job added! A position named ".$nudata['jobtitle']." has been just posted by you, ".$nudata['posterid'].".")
                ->setHTMLBody("<p>Hello, this email confirms a job offer at refair.me.<br/><a href=\"".$burl."/show/job/".$ref['id']."\">Click this URL to see the job posting</a><br/>You should see a new referral alert every time someone refers a friend or or applies to your posting.</p>".
                    "<a href=\"".$burl."/optin/".$nuhash."\">Share this url with your candidates to sign them off on your job post!</a>");
            $this->mailer->send($mail);
            $this->flash->addMessage('info','Thank you for using refair.me. Your job '.$nudata['jobtitle'].' had been added. We wish you a successfull hire.' );
            //}

            return $burl."/show/job/".$ref['id'].print_r($ref);
        }catch (PDOException $e){
            return json_encode($e);
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

    public function filelist($request, $response, $args){
        $target = $request->getParam('target');

        foreach (new DirectoryIterator('/usr/share/nginx/html/storage/'.$target) as $file) {
            if ($file->isFile()) {
                print $file->getFilename() . "\n";
            }
        }
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

    public function processOauth($request, $response, $args){
        $burl = env('BASE_URL');

        $provider = new OauthLI([
            'clientId'          => '78wq38drug572o',
            'clientSecret'      => '0xDQcGgexlkIhh7z',
            'redirectUri'       => $burl.'/processoauth']);
        $session = new Session;

        if (!isset($_GET['code'])) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $session['oauth2state'] = $provider->getState();
            header('Location: '.$authUrl);
            exit;
            // Check given state against previously stored one to mitigate CSRF attack
        } elseif ( !strcmp($session['oauth2state'], $request->getParam('state')) ) {
            //Compare session state with state from LI
            //TODO: This is needed otherwise the system fails!

            print_r("<pre>");
            print_r($session['oauth2state']);
            print_r("<br/>");
            print_r("<br/>");
            print_r($request->getParam('state'));
            print_r("</pre>");
            unset($session['oauth2state']);
            exit('Invalid state');
        } else {
            try {
                print_r("TEST2");
                // Try to get an access token (using the authorization code grant)
                // Optional: Now you have a token you can look up a users profile data
                $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
                // We got an access token, let's now get the user's details
                $user = $provider->getResourceOwner($token);
                // Use these details to create a new profile

                $firstname = $user->getFirstname();
                $lastname = $user->getLastname();
                $limail = $user->getEmail();

                $useraa = json_decode(User::where('email', $limail)->get(), true)[0];

                $token->getToken();
                //Set token for this session
                $this->session['oauth_li_token']=$token;

                if( !strcmp($useraa['id'], '') ){
                    print_r("TEST3 - user does not exist in database");
                    //We did not find the user from his linkedin email
                    //Let's see if he's an OAuth2 client

                    print_r("TEST4 - user not exists as oauth client");
                    //User does not exist in Users table, as well as does not exist in oauth clients
                    //We need to create the user and the oauth client
                    $burl = env('BASE_URL');
                    $acode = md5('prizm-rocks', date('Y M H D S'));
                    $uniqueId = 'prizm';
                    $uniqueId = $this->iwahash($uniqueId, "UPASS:DEFAULTLI".$acode);
                    $uniqueId = $this->iwahash($uniqueId, "UMAIL:".$limail);
                    $uniqueId = $this->iwahash($uniqueId, "TOKEN:".$token );
                    $uniqueId = $this->iwahash($uniqueId, "DATE:".date('Ymdhis'));

                    $oaclient = OA2Clients::create( ['client_id' => $token ,
                        'client_secret' => $uniqueId,
                        'redirect_url' => $burl.'/',
                        'grant_types' => 'MINIMAL',
                        'scope' => 'DEFAULT',
                        'user_id' => $limail
                    ]);
                    //Our LI client is now registered to log in via LI next time
                    //Creating the user if not exists, loggin him in and redirecting to service
                    $user = User::create(['email' => $limail,
                        'name' => $firstname.md5(date('Ymdhs')), //RANDOM NAME
                        'password' => password_hash('DEFAULTLI'.$acode, PASSWORD_DEFAULT),
                        'activ_code' => $uniqueId, // <-- add the activation code to database
                        'group_id' => 4, //for LinkedinImported,
                        'cvadded' => false
                    ]);

                    Requests::register_autoloader();
                    $userDats = Requests::get('https://api.linkedin.com/v1/people/~?format=json&oauth2_access_token='.$token);
                    //	    $userSkills = \Requests::get('https://api.linkedin.com/v1/people/id='.$userDats['id'].'/skills~?format=json&oauth2_access_token='.$token);
                    //After having a token, the request above supposedly takes the profile data
                    //We store it in the tags of LinkedinImport

                    Linkedinimport::create(['firstName'=>$firstname,
                        'lastName'=>$lastname,
                        'company'=>"BASIC PROFILE PERMISSIONS - UPDATE YOUR LI PERMISSIONS ",
                        'title'=>"BASIC PROFILE PERMISSIONS - hidden",
                        'email'=>$limail,
                        'skills'=>json_encode($userSkills, true),
                        'tags'=>json_encode($userDats,true),
                        'uidInviter'=>$uniqueId]);

                    $command = 'sudo /usr/share/nginx/refair/resources/pythonapis/match/API/run.py '.$userDats->headline;
                    $returned = $this->my_shell_exec($command,$retun,$rettwo);
                    $weights = json_decode($retun,true)['predictions']; //get array of predictions

                    Userweight::create(['userid'=>$user->id,
                        'aone'=>$weights[0],
                        'atwo'=>$weights[1],
                        'athree'=>$weights[2],
                        'afour'=>$weights[3],
                        'afive'=>$weights[4],
                        'asix'=>$weights[5],
                        'aseven'=>$weights[6],
                        'aeight'=>$weights[7],
                        'anine'=>$weights[8],
                        'aten'=>$weights[9],
                        'aeleven'=>$weights[10]
                    ]);
                    //TODO:uidInviter used as id to client_id of OA2Clients. Rephrase this

                    //Preactivate user
                    $editableuser = User::where('email', $limail);
                    $editableuser->activ = 1;
                    $editableuser->update(['activ'=>1]);

                    $burl = env('BASE_URL');
                    $mail = new Message;
                    $mail->setFrom(env('MAIL_USERNAME'))
                        ->addTo($limail)
                        ->setSubject('Hi '.$limail.' ! Refair.me welcomes you. Thank you for connecting with LinkedIn.')
                        ->setHTMLBody("Hello, this email confirms you logged into refair.me for the first time ever, using LinkedIn.".
                            "Please take a moment to <b> read this email</b>. Your account is currently secured by a randomly generated password and refair.me will keep on using your LI credentials to log you in every time.".
                            "<h3>If you would like to switch to logging in with refair.me, please reset your password. Your LinkedIn logins will work as always, but you will be able to log in directly to Refair.me</h3>".
                            "The link under which you can set up your password is available <a href=\"".$burl ."/auth/recover/".$recoverHash."\"> here </a>");
                    //TODO: FIX BASE URL IN LINKEDIN FRESH LOGIN
                    $this->mailer->send($mail);
                    $this->flash->addMessage('info','Thank you for logging in using LinkedIn! We redirected you your dashboard!');

                    //Log user with fresh creds
                    $auth = $this->auth->attempt( $limail,"DEFAULTLI".$acode );

                    if($auth){
                        if($session['buildprofile']){
                            // If we came here from profile build, let's return to the profile builder
                            return $response->withRedirect($this->router->pathFor('refair.buildprofile'));
                        }else{
                            // We came from somewhere else, registered and logged in. Go to home
                            return $response->withRedirect($this->router->pathFor('home'));
                        }
                    }else{
                        throw "Autologin failed";
                    }
                    //OAuth2 client is added
                    //User is created
                    //User is preactivated and does not need an activation code
                }else{
                    print_r("TEST8 - user exists ");
                    $oaclient = OA2Clients::where('user_id',$limail) or die('cant get oaclient');
                    //We have the oa client from before
                    $auser = User::where('email', $limail) or die("failed getting user from db");
                    $myhash = json_decode($auser->get(),true)[0]['activ_code'];
                    print_r($myhash);
                    $unwrap = array();
                    $data = $this->iwadehash($myhash, $unwrap);
                    $operands = $this->getOps(explode('~', $data));
                    print_r($operands);
                    $nuhash = json_decode($oaclient->get(),true)[0]['client_secret'];
                    $unwrapu = array();
                    $datanu = $this->iwadehash($nuhash, $unwrapu);
                    $operandsnu = $this->getOps(explode('~', $datanu));
                    print_r($operandsnu);


                    $auth = $this->auth->attempt( $limail,
                        $operandsnu['UPASS'] );
                    if (!$auth) {
                        print_r("TEST6 - failed auth normal");
                        $auth = $this->auth->attempt( $limail,
                            $operands['UPASS'] );
                        if (!$auth) {
                            print_r("TEST6 - failed auth");
                            $this->flash->addMessage('error','Something failed while logging with LinkedIn. Please contact support@techsorted.com.');
                            return $response->withRedirect($this->router->pathFor('auth.signin'));
                        }else{
                            print_r("TEST6 - success auth");
                            //Succes
                            $this->flash->addMessage('info','Thank you for using refair.me!');

                            if($session['buildprofile']){
                                return $response->withRedirect($this->router->pathFor('refair.buildprofile'));
                            } else {
                                return $response->withRedirect($this->router->pathFor('home'));
                            }
                        }

                        print_r("HERE");
                    }else{
                        print_r("Should redirect");
                        $url=env('BASE_URL');
                        $mail = new Message;
                        $mail->setFrom(env('MAIL_USERNAME'))
                            ->addTo($limail)
                            ->setSubject('Refair.me login confirmation via LinkedIn, on '.date('Ymhds'))
                            ->setHTMLBody("Hello, this email confirms you logged in to Refair.me on .".date('Ymhds').".<br/> If it was not you, please click: <br /><a href=\"".
                                $url . "/auth/recover\"> this link to quickly recover your account</a><h3>Thank you for using Refair.me</h3>");
                        $this->mailer->send($mail);
                        $this->flash->addMessage('info','Thank you for logging into Refair.me using LinkedIn!');

                        if($session['buildprofile']){
                            return $response->withRedirect($this->router->pathFor('refair.buildprofile'));
                        } else {
                            return $response->withRedirect($this->router->pathFor('home'));
                        }
                    }
                }
            }catch (Exception $e) {
                // Failed to get user details
                exit('Oh dear...');
            }
        }
    }

    public function processgithuboauth($request, $response, $args){
        $burl = env('BASE_URL');

        $provider = new OauthGH([
            'clientId'          => 'd0fdd90b1907699b4f60',
            'clientSecret'      => 'a336d077030896c838a48decfbaa6bd935a0ac4b',
            'redirectUri'       => $burl.'/processgithub']);
        $session = new Session;

        print_r("TEST1");

        if (!isset($_GET['code'])) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $session['oauth2state'] = $provider->getState();
            header('Location: '.$authUrl);
            exit;
            // Check given state against previously stored one to mitigate CSRF attack
        } elseif ( !strcmp($session['oauth2state'], $request->getParam('state')) ) {
            //Compare session state with state from LI
            //TODO: This is needed otherwise the system fails!

            print_r("<pre>");
            print_r($session['oauth2state']);
            print_r("<br/>");
            print_r("<br/>");
            print_r($request->getParam('state'));
            print_r("</pre>");
            unset($session['oauth2state']);
            exit('Invalid state');
        } else {
            try {
                print_r("TEST2");
                // Try to get an access token (using the authorization code grant)
                // Optional: Now you have a token you can look up a users profile data
                $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
                // We got an access token, let's now get the user's details
                $user = $provider->getResourceOwner($token);
                // Use these details to create a new profile

                $firstname = $user->getFirstname();
                $lastname = $user->getLastname();
                $limail = $user->getEmail();

                $useraa = json_decode(User::where('email', $limail)->get(), true)[0];

                $token->getToken();
                //Set token for this session
                $this->session['oauth_li_token']=$token;

                if( !strcmp($useraa['id'], '') ){
                    print_r("TEST3 - user does not exist in database");
                    //We did not find the user from his linkedin email
                    //Let's see if he's an OAuth2 client

                    print_r("TEST4 - user not exists as oauth client");
                    //User does not exist in Users table, as well as does not exist in oauth clients
                    //We need to create the user and the oauth client
                    $burl = env('BASE_URL');
                    $acode = md5('prizm-rocks', date('Y M H D S'));
                    $uniqueId = 'prizm';
                    $uniqueId = $this->iwahash($uniqueId, "UPASS:DEFAULTLI".$acode);
                    $uniqueId = $this->iwahash($uniqueId, "UMAIL:".$limail);
                    $uniqueId = $this->iwahash($uniqueId, "TOKEN:".$token );
                    $uniqueId = $this->iwahash($uniqueId, "DATE:".date('Ymdhis'));

                    $oaclient = OA2Clients::create( ['client_id' => $token ,
                        'client_secret' => $uniqueId,
                        'redirect_url' => $burl.'/',
                        'grant_types' => 'MINIMAL',
                        'scope' => 'DEFAULT',
                        'user_id' => $limail
                    ]);
                    //Our LI client is now registered to log in via LI next time
                    //Creating the user if not exists, loggin him in and redirecting to service
                    $user = User::create(['email' => $limail,
                        'name' => $firstname.md5(date('Ymdhs')), //RANDOM NAME
                        'password' => password_hash('DEFAULTLI'.$acode, PASSWORD_DEFAULT),
                        'activ_code' => $uniqueId, // <-- add the activation code to database
                        'group_id' => 4, //for LinkedinImported,
                        'cvadded' => false
                    ]);

                    Requests::register_autoloader();
                    $userDats = Requests::get('https://api.linkedin.com/v1/people/~?format=json&oauth2_access_token='.$token);
                    //After having a token, the request above supposedly takes the profile data
                    //We store it in the tags of LinkedinImport

                    Linkedinimport::create(['firstName'=>$firstname,
                        'lastName'=>$lastname,
                        'company'=>"BASIC PROFILE PERMISSIONS - UPDATE YOUR LI PERMISSIONS ",
                        'title'=>"BASIC PROFILE PERMISSIONS - hidden",
                        'email'=>$limail,
                        'tags'=>json_encode($userDats,true),
                        'uidInviter'=>$uniqueId]);
                    //TODO:uidInviter used as id to client_id of OA2Clients. Rephrase this

                    //Preactivate user
                    $editableuser = User::where('email', $limail);
                    $editableuser->activ = 1;
                    $editableuser->update(['activ'=>1]);

                    $burl = env('BASE_URL');
                    $mail = new Message;
                    $mail->setFrom(env('MAIL_USERNAME'))
                        ->addTo($limail)
                        ->setSubject('Hi '.$limail.' ! Refair.me welcomes you. Thank you for connecting with LinkedIn.')
                        ->setHTMLBody("Hello, this email confirms you logged into refair.me for the first time ever, using LinkedIn.".
                            "Please take a moment to <b> read this email</b>. Your account is currently secured by a randomly generated password and refair.me will keep on using your LI credentials to log you in every time.".
                            "<h3>If you would like to switch to logging in with refair.me, please reset your password. Your LinkedIn logins will work as always, but you will be able to log in directly to Refair.me</h3>".
                            "The link under which you can set up your password is available <a href=\"".$burl ."/auth/recover/".$recoverHash."\"> here </a>");
                    //TODO: FIX BASE URL IN LINKEDIN FRESH LOGIN
                    $this->mailer->send($mail);
                    $this->flash->addMessage('info','Thank you for logging in using LinkedIn! We redirected you your dashboard!');

                    //Log user with fresh creds
                    $auth = $this->auth->attempt( $limail,"DEFAULTLI".$acode );

                    if($auth){
                        return $response->withRedirect($this->router->pathFor('home'));
                    }else{
                        throw "Autologin failed";
                    }
                    //OAuth2 client is added
                    //User is created
                    //User is preactivated and does not need an activation code
                }else{
                    print_r("TEST8 - user exists ");
                    $oaclient = OA2Clients::where('user_id',$limail) or die('cant get oaclient');
                    //We have the oa client from before
                    $auser = User::where('email', $limail) or die("failed getting user from db");
                    $myhash = json_decode($auser->get(),true)[0]['activ_code'];
                    print_r($myhash);
                    $unwrap = array();
                    $data = $this->iwadehash($myhash, $unwrap);
                    $operands = $this->getOps(explode('~', $data));
                    print_r($operands);
                    $nuhash = json_decode($oaclient->get(),true)[0]['client_secret'];
                    $unwrapu = array();
                    $datanu = $this->iwadehash($nuhash, $unwrapu);
                    $operandsnu = $this->getOps(explode('~', $datanu));
                    print_r($operandsnu);


                    $auth = $this->auth->attempt( $limail,
                        $operandsnu['UPASS'] );
                    if (!$auth) {
                        print_r("TEST6 - failed auth normal");
                        $auth = $this->auth->attempt( $limail,
                            $operands['UPASS'] );
                        if (!$auth) {
                            print_r("TEST6 - failed auth");
                            $this->flash->addMessage('error','Something failed while logging with LinkedIn. Please contact support@techsorted.com.');
                            return $response->withRedirect($this->router->pathFor('auth.signin'));
                        }else{
                            print_r("TEST6 - success auth");
                            //Succes
                            $this->flash->addMessage('info','Thank you for using refair.me!');
                            return $response->withRedirect($this->router->pathFor('home'));
                        }

                        print_r("HERE");
                    }else{
                        print_r("Should redirect");
                        $url=env('BASE_URL');
                        $mail = new Message;
                        $mail->setFrom(env('MAIL_USERNAME'))
                            ->addTo($limail)
                            ->setSubject('Refair.me login confirmation via LinkedIn, on '.date('Ymhds'))
                            ->setHTMLBody("Hello, this email confirms you logged in to Refair.me on .".date('Ymhds').".<br/> If it was not you, please click: <br /><a href=\"".
                                $url . "/auth/recover\"> this link to quickly recover your account</a><h3>Thank you for using Refair.me</h3>");
                        $this->mailer->send($mail);
                        $this->flash->addMessage('info','Thank you for logging into Refair.me using LinkedIn!');
                        return $response->withRedirect($this->router->pathFor('home'));
                    }
                    print_r("never should be here");
                }
            }catch (Exception $e) {
                // Failed to get user details
                exit('Oh dear...');
            }
        }
    }


    public function landingpage($request, $response, $args){
        $view = 'landing-boot.vue';
        return $this->view->render($response,
            $view,
            array('menus'=>$menus ,
                'location'=>$data
            ));


    }


    public function gaetfile($request, $response, $args){
        $filename=$args['signoffhash'];
        $file = '/usr/share/nginx/html/storage/cvs/'.$filename;
        $fh = fopen($file, 'rb');

        $stream = new Stream($fh); // create a stream instance for the response body

        return $response->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody($stream); // all stream contents will be sent to the response
    }

    public function getBuildProfile($request,$response)
    {
        $session = new Session;
        $session['buildprofile'] = true;
        $menus = $this->buildmenu();
        //Get the vue booter for profilebuild component
        return $this->view->render($response,'profilebuild-boot.twig', array('menus'=>$menus));
    }

}
