<?php
namespace App\Controllers;
use Exception;
use Nette\Mail\Message;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\UserExperience;
use App\Models\Company;
use App\Models\Cart;
use App\Models\File;
use App\Models\Referral;
use App\Models\JobDesc;
use App\Models\JobWeight;
use App\Models\UserWeight;
use App\Models\Linkedinimport;
use App\Models\Signoff;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RefairController extends Controller {
    // Inject logger or other dependencies directly
    public function __construct()
    {
    }

    public function index(Request $request, Response $response, $args)
    {
        $payload = json_encode(['message' => 'Access available only via client applications']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');;
    }

    function throwIfNone(&$val){
        if( ($val === '') || ($val === NULL) ){
            throw new Exception("Value should not be empty : ".print_r($val));
        }
    }

    function uploadFile($request, $response, $args){
        $directory = env('UPLOAD_DIRECTORY');
        $uploadedFiles = $request->getUploadedFiles();
        $data = $request->getParsedBody();

        $this->throwIfNone($data['email']);

        // handle single input with single file upload
        $uploadedFile = $uploadedFiles['file'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

            $filename = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
            $basename = $filename.'~'.bin2hex(random_bytes(8)).'~'.$data['email']; // see http://php.net/manual/en/function.random-bytes.php
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

            $filename =  $basename.'.'.$extension;

            $dat='prizm';
            $nuhash = $this->iwahash($dat,'ORIGIN',$dat);
            $nuhash = $this->iwahash($nuhash,"FILENAME",$filename);
            $nuhash = $this->iwahash($nuhash,"TIMESTAMP", date("Y-m-d H:i:s",time()) );

            $title = json_encode( ['email' => $data['email'],
                'title' => $data['title'] ], true);

            $fileUploaded = File::create(['filename'=>$filename,
                'title' => $title,
                'hash' => $nuhash,
                'email' => $data['email']
            ]);

            $totalUri = $directory . DIRECTORY_SEPARATOR . $filename;

            $uploadedFile->moveTo($totalUri);

            return $response->withJson(['status'=>'success',
                'message'=> 'Succesfully uploaded ' . $filename ,
                'file'=>$fileUploaded]);
        }else{
            return $response->withJson(['status'=>'error',
                'message'=> 'Could not upload']);

        }
    }

    public function evalkeywords(Request $request, Response $response, array $args): Response{
        try{
            $queryAll = $request->getQueryParams();
            $query = urldecode($queryAll['eval']);

            $retarr = ["results"=>[json_encode($query,true)]];

            if(strlen($query) < 3){
                throw new \Exception("The input array cannot be empty");
            } else {
                $command = "source /var/www/html/models/match/venv/bin/activate && /var/www/html/models/match/venv/bin/python /var/www/html/models/match/run.py \"{$query}\"";
                $returned = shell_exec($command) or die("Evaluation not operational");
                $weights = json_decode($returned,true); //get array of predictions

                $response->getBody()->write(json_encode($weights));
                return $response;
            }
        }catch(\Exception $e){
            $response->withStatus(422)->getBody()->write(json_encode(["status"=>"error",
                "message"=>"Input array cannot be empty, {$e->getMessage()}"]));
            return $response;
        }
    }

    public function storeprofile($request, $response, $args)
    {
        try {
            $getData = $request->getParsedBody();
            $weights = $getData['params']['weights'];
            $uid = $getData['params']['unique_id'];

            $auser = User::where('unique_id', $uid)->first();
            $auser->first_name = $getData['params']['firstname'];
            $auser->last_name = $getData['params']['lastname'];
            $auser->save();

            //TODO: No keywords passed yet;
            //$kws = $getData['params']['keywords'];

            $uweight = UserWeight::updateOrCreate(['user_id' => $auser->id], [
                'weight_one' => $weights[0],
                'weight_two' => $weights[1],
                'weight_three' => $weights[2],
                'weight_four' => $weights[3],
                'weight_five' => $weights[4],
                'weight_six' => $weights[5],
                'weight_seven' => $weights[6],
                'weight_eight' => $weights[7],
                'weight_nine' => $weights[8],
                'weight_ten' => $weights[9],
                'weight_eleven' => $weights[10],
            ]);
            $uweight->user_id = $auser->id;

            //TODO: No keywords passed yet;
            //$uweight->keywords = $kws;

            $uweight->save();

            $udesc = UserDescription::updateOrCreate(['user_id' => $auser->id], [
                'keywords' => $getData['params']['keywords'],
                'skills' => $getData['params']['skills'],
                'notice_period' => $getData['params']['noticePeriod'],
                'availability' => $getData['params']['availability'],
                'expected_salary' => $getData['params']['expectedSalary'],
                'job_status' => $getData['params']['jobStatus'],
            ]);
            $udesc->user_id = $auser->id;
            $udesc->save();

            $response->getBody()->write(json_encode(['status' => "success",
                //'keywords' => join(',', $kws),
                'message' => "Succesfully updated profile for user "]));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

            //TODO: Returned function early - edit when table supports additional columns;
            $askills = $getData['params']['skills'];
            $auser->skills = $askills;

            $askillsNice = $getData['params']['skills_nice'];
            $auser->skills_nice = $askillsNice;

            $aframeworksMust = $getData['params']['frameworks_must'];
            $auser->frameworks_must = $aframeworksMust;

            $aframeworksNice = $getData['params']['frameworks_nice'];
            $auser->frameworks_nice = $aframeworksNice;

            $amethodologiesMust = $getData['params']['methodologies_must'];
            $auser->methodologies_must = $amethodologiesMust;

            $amethodologiesNice = $getData['params']['methodologies_nice'];
            $auser->methodologies_nice = $amethodologiesNice;

            $avail = $getData['params']['availability'];
            $auser->scheduling = $avail;

            $salary = $getData['params']['expectedSalary'];
            $auser->salary_expectation = $salary;

            $period = $getData['params']['noticePeriod'];
            $auser->notice_period = $period;

            $aexp = $getData['params']['exp'];
            $auser->exp = $aexp;

            $auser->save();

            return $response->withJson(['status'=>"success",
                'keywords'=>join(',',$kws),
                'message'=>"Succesfully updated profile for user "]);
        }catch(Exception $e){
            print_r($e); //WTF 65 MB of exception!?
        }
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getProfile(Request $request,Response $response, $args): Response
    {
        try {
            // Use the unique id passed via the URL to get the user.
            $uid = $args['id'];
            $user = User::where('unique_id', $uid)->first();
            if (!$user) {
                throw new \Exception("User not found");
            }

            // Retrieve the most recent userweight record for this user.
            $userweight = UserWeight::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Define our canonical ordering of the canonicalCategories.
            $canonicalCategories = [
                'Backend',
                'Full Stack',
                'Devops',
                'Project Manager',
                'Business Analyst',
                'Business Intelligence',
                'Frontend',
                'Mobile & Embedded',
                'HR',
                'Marketing',
                'Other'
            ];

            // If no record is found, return an array of zeros.
            if (!$userweight) {
                $returnedWeights = array_fill(0, count($canonicalCategories), 0);
            } else {
                // Assume that the "weights" column now stores the classifier's JSON output.
                $rawWeights = $userweight['weights'];
                $decodedWeights = json_decode($rawWeights, true);
                if ($decodedWeights === null) {
                    // Fallback to zeros if JSON decoding fails.
                    $returnedWeights = array_fill(0, count($canonicalCategories), 0);
                } else {
                    // Check whether the decoded JSON is in classifier style
                    // (i.e. an array of objects where each has a 'predictions' key).
                    if (isset($decodedWeights[0]) && isset($decodedWeights[0]['predictions'])) {
                        $aggregated = [];
                        foreach ($decodedWeights as $result) {
                            if (isset($result['predictions']['predictions']) && is_array($result['predictions']['predictions'])) {
                                foreach ($result['predictions']['predictions'] as $cat => $score) {
                                    // Keep the highest score seen per category.
                                    if (!isset($aggregated[$cat]) || $score > $aggregated[$cat]) {
                                        $aggregated[$cat] = $score;
                                    }
                                }
                            }
                        }
                        // Build an ordered array based on our canonical category list.
                        $returnedWeights = [];
                        foreach ($canonicalCategories as $cat) {
                            $returnedWeights[] = $aggregated[$cat] ?? 0;
                        }
                    } else {
                        // Fallback if the JSON structure is already aggregated (associative keyed by category).
                        $returnedWeights = [];
                        foreach ($canonicalCategories as $cat) {
                            $returnedWeights[] = $decodedWeights[$cat] ?? 0;
                        }
                    }
                }
            }

            // Retrieve the latest user description for extra profile details.
            $userDescription = UserDescription::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Build the response data.
            $responseData = [
                'success'         => true,
                'message'         => "Returned profile for user " . $user->id,
                'firstname'       => $user->first_name,
                'lastname'        => $user->last_name,
                'email'           => $user->email,
                'id'              => $user->id,
                'weights'         => $returnedWeights,
                'jobStatus'       => $userDescription->job_status ?? 'not looking',
                'keywords'        => $userDescription->keywords ?? [],
                'exp'             => $user->exp,
                'availability'    => $userDescription->availability ?? '',
                'noticePeriod'    => $userDescription->notice_period ?? '',
                'expectedSalary'  => $userDescription->expected_salary ?? '',
                'skills'          => $userDescription->skills ?? [],
                'role'            => $user->current_role,
                'skills_nice'     => empty($user->skills_nice) ? [] : $user->skills_nice,
                'frameworks_must' => empty($user->frameworks_must) ? [] : $user->frameworks_must,
                'frameworks_nice' => empty($user->frameworks_nice) ? [] : $user->frameworks_nice,
                'methodologies_must' => empty($user->methodologies_must) ? [] : $user->methodologies_must,
                'methodologies_nice' => empty($user->methodologies_nice) ? [] : $user->methodologies_nice
            ];

            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => "An error occurred: " . $e->getMessage()
            ]));
            return $response;
        }
    }


    /**
     * @param $request
     * @param $response
     * @param $args
     * @return void
     */
    public function storeExperience(Request $request,Response $response, $args) : Response
    {
        try {
            $getData = $request->getParsedBody();
            $jobName = $getData['params']['name'];
            $role = $getData['params']['role'];
            $startDate = $getData['params']['start'];
            $responsibilities = $getData['params']['responsibilities'];
            $currentJob = $getData['params']['currentJob'];
            $endDate = $getData['params']['end'] ?? null;
            $years = $getData['params']['years'];
            $uid = $getData['params']['unique_id'];
            $salary = $getData['params']['salary'] ?? null;

            $auser = User::where('unique_id', $uid)->first();

            $userexp = UserExperience::firstOrCreate(['user_id' => $auser->id,
            'name' => $jobName,
            'role' => $role,
            'start' => $startDate], [
            'responsibilities' => $responsibilities,
            'current_job' => $currentJob,
            'end' => $endDate ?? null,
            'years' => $years,
            'salary' => $salary
            ]);

            $userexp->save();

            $response->getBody()->write(json_encode(['status' => "success",
                'message' => 'Experienced saved succesfully']));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $e) {
            print_r($e);   
        }
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return void
     */
    public function getExperience(Request $request, Response $response, $args): Response
    {
        try {
            //id is unique_id;
            $uid = $args['id'];
            $auid = User::where('unique_id', $uid)->first();

            $userexp = UserExperience::where('user_id', $auid->id)->get()->toArray();

            $response->getBody()->write(json_encode(['status' => "success",
                'exp' => $userexp,
                'message' => "Getting experience for user"]));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $e) {
            print_r($e);   
        }
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return void
     */
    public function deleteExperience(Request $request,Response $response, $args): Response
    {
        try {
            $getData = $request->getParsedBody();
            $exp = $getData['params']['id'];

            $expToDelete = UserExperience::where('id', $exp)->delete();

            $response->getBody()->write(json_encode(['status' => "success",
                'message' => "Experience deleted successfully"]));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);


        } catch (\Exception $e) {
            print_r($e);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function matchProfile(Request $request, Response $response, $args): Response {
        try {
            // Retrieve and decode the input JSON from the request body.
            $getVars = $request->getParsedBody();
            $passedWeightsDecoded = json_decode($getVars['passedWeights'], true);
            if ($passedWeightsDecoded === null) {
                throw new Exception("Invalid JSON input");
            }

            // Define the canonical order of categories.
            $categories = [
                'Backend', 'Full Stack', 'Devops', 'Project Manager',
                'Business Analyst', 'Business Intelligence', 'Frontend',
                'Mobile & Embedded', 'HR', 'Marketing', 'Other'
            ];

            // Process candidate input.
            // If the input has classifier style results (an array of objects with a predictions key),
            // aggregate them into a simple array keyed by category.
            if (isset($passedWeightsDecoded[0]) && isset($passedWeightsDecoded[0]['predictions'])) {
                $aggregatedCandidate = [];
                foreach ($passedWeightsDecoded as $result) {
                    if (isset($result['predictions']['predictions']) && is_array($result['predictions']['predictions'])) {
                        foreach ($result['predictions']['predictions'] as $cat => $score) {
                            // Keep the highest score seen for each category.
                            if (!isset($aggregatedCandidate[$cat]) || $score > $aggregatedCandidate[$cat]) {
                                $aggregatedCandidate[$cat] = $score;
                            }
                        }
                    }
                }
                $candidateWeights = [];
                foreach ($categories as $cat) {
                    $candidateWeights[] = isset($aggregatedCandidate[$cat]) ? $aggregatedCandidate[$cat] : 0;
                }
            } else {
                // If already aggregated, assume it's an array of length 11 corresponding to the defined categories.
                $candidateWeights = $passedWeightsDecoded;
            }

            // Retrieve job weight records from the database.
            $jobweights = JobWeight::orderBy('created_at')->get()->toArray();
            $retarr = [];
            $threshold = 0.1;

            foreach ($jobweights as $jobweight) {
                // Decode the JSON stored in the new "weights" column.
                $jobClassifierResults = json_decode($jobweight['weights'], true);
                $aggregatedJobWeights = [];

                if (is_array($jobClassifierResults)) {
                    foreach ($jobClassifierResults as $result) {
                        if (isset($result['predictions']['predictions']) && is_array($result['predictions']['predictions'])) {
                            foreach ($result['predictions']['predictions'] as $cat => $score) {
                                if (!isset($aggregatedJobWeights[$cat]) || $score > $aggregatedJobWeights[$cat]) {
                                    $aggregatedJobWeights[$cat] = $score;
                                }
                            }
                        }
                    }
                }

                // Build an ordered array of job weights using the canonical category list.
                $jobWeightsArray = [];
                foreach ($categories as $cat) {
                    $jobWeightsArray[] = $aggregatedJobWeights[$cat] ?? 0;
                }

                // Check if at least one category meets the threshold condition.
                $matchFound = false;
                foreach ($jobWeightsArray as $index => $jobScore) {
                    if ($candidateWeights[$index] >= $threshold && abs($candidateWeights[$index] - $jobScore) <= $threshold) {
                        $matchFound = true;
                        break;
                    }
                }
                if (!$matchFound) {
                    continue; // Skip this job record if no match exists.
                }

                // Retrieve the corresponding job description using the jobid column.
                $jobid = $jobweight['jobid'];
                $jobdsc = JobDesc::where('id', $jobid)
                    ->orderBy('created_at', 'desc')
                    ->with('company')
                    ->first();

                if (!is_null($jobdsc)) {
                    // Assuming cc() is a custom conversion function.
                    $jobdsc = cc($jobdsc->toArray());
                    $jobdsc['keywords'] = explode(',', $jobdsc['keywords']);
                }

                // Prevent duplicate entries.
                $duplicate = false;
                foreach ($retarr as $existingJob) {
                    if ($existingJob['id'] === $jobid) {
                        $duplicate = true;
                        break;
                    }
                }
                if ($duplicate) {
                    continue;
                }

                // Append the aggregated job weights.
                $jobdsc['weights'] = $jobWeightsArray;
                $retarr[] = $jobdsc;
            }

            // If no jobs were matched, return an error message.
            if (empty($retarr)) {
                $retarr = [
                    "state"   => "error",
                    "message" => "There are no jobs in the system that fit your criteria"
                ];
            }

            $response->getBody()->write(json_encode($retarr));
            return $response;
        } catch (Exception $e) {
            $response->withStatus(500)->getBody()->write(json_encode([
                "state"   => "error",
                "message" => "An error occurred: " . $e->getMessage()
            ]));

            return $response;
        }
    }


    public function matchjob($request, $response, $args){
        try{

            //Expected is JSON
            $getVars =  $request->getParams();

            $jobid = $args['id'];

            $weights = json_decode(JobWeight::where('jobid',$jobid)->get(),true)[0];

            $userweights = json_decode(UserWeight::all(),true);
            $retarr = [];

            $threshold = 0.1;

            foreach($userweights as $userweight){

                if( (($userweight['aone']-$threshold) <= $weights['aone']) && ($weights['aone'] <= ($userweight['aone']+$threshold)) && ( $weights[0] >= $threshold ) ){ goto processAI;}
                if( (($userweight['atwo']-$threshold) <= $weights['atwo']) && ($weights['atwo'] <= ($userweight['atwo']+$threshold)) && ( $weights[1] >= $threshold )  ){ goto processAI;}
                if( (($userweight['athree']-$threshold) <= $weights['athree']) && ($weights['athree'] <= ($userweight['athree']+$threshold)) && ( $weights[2] >= $threshold ) ){ goto processAI;}
                if( (($userweight['afour']-$threshold) <= $weights['afour']) && ($weights['afour'] <= ($userweight['afour']+$threshold)) && ( $weights[3] >= $threshold ) ){ goto processAI;}
                if( (($userweight['afive']-$threshold) <= $weights['afive']) && ($weights['afive'] <= ($userweight['afive']+$threshold)) && ( $weights[4] >= $threshold ) ){ goto processAI;}
                if( (($userweight['asix']-$threshold) <= $weights['asix']) && ($weights['asix'] <= ($userweight['asix']+$threshold)) && ( $weights[5] >= $threshold ) ){ goto processAI;}
                if( (($userweight['aseven']-$threshold) <= $weights['aseven']) && ($weights['aseven'] <= ($userweight['aseven']+$threshold)) && ( $weights[6] >= $threshold ) ){ goto processAI;}
                if( (($userweight['aeight']-$threshold) <= $weights['aeight']) && ($weights['aeight'] <= ($userweight['aeight']+$threshold)) && ( $weights[7] >= $threshold ) ){ goto processAI;}
                if( (($userweight['anine']-$threshold) <= $weights['anine']) && ($weights['anine'] <= ($userweight['anine']+$threshold)) && ( $weights[8] >= $threshold ) ){ goto processAI;}
                if( (($userweight['aten']-$threshold) <= $weights['aten']) && ($weights['aten'] <= ($userweight['aten']+$threshold)) && ( $weights[9] >= $threshold )  ){ goto processAI;}
                if( (($userweight['aeleven']-$threshold) <= $weights['aeleven']) && ($weights['aeleven'] <= ($userweight['aeleven']+$threshold)) && ( $weights[10] >= $threshold ) ){ goto processAI;}

                goto finished;

                processAI:
                $retdata = ['id'=>$userweight['userid']];
                $retdata[] = $userweight['aone'];
                $retdata[] = $userweight['atwo'];
                $retdata[] = $userweight['athree'];
                $retdata[] = $userweight['afour'];
                $retdata[] = $userweight['afive'];
                $retdata[] = $userweight['asix'];
                $retdata[] = $userweight['aseven'];
                $retdata[] = $userweight['aeight'];
                $retdata[] = $userweight['anine'];
                $retdata[] = $userweight['aten'];
                $retdata[] = $userweight['aeleven'];
                $retdata['keywords'] = $userweight['keywords'];
                $retarr[] = $retdata;

                finished:
            }

            if(count($retarr) == 0){
                $retarr['state']="error";
                $retarr['message']="There are no users in the system that fit your criteria";
            }

            return $response->withJson($retarr);

        }catch(Exception $e){
            print_r($e);
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
        }
    }

    public function postlocation($request, $response, $args){
        try{


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

    public function postreferral($request, $response, $args){
        try{
            //TODO: LEAVING this function because referrals are coming fast.
            //TODO: only for reference, I propose to refactor this
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
                    "~LOCID:".$nudata['locationid'].
                    "~KEYWORDS:".$nudata['keywords'].
                    $ref['regdate'].
                    "~STATE:FIRST SHOT"))
            );

            $ref->save();

            $job = json_decode(JobDesc::where('id',$ref['jobid'])->get(), true)[0];
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

    /*  function returnJob($aproj){
        $job = $aproj;
        $job['exp']=json_decode($job['exp'],true);
        $job['fund']=json_decode($job['fund'],true);
        $job['keywords']= explode(',',$job['keywords']);
        $job['contractType']=json_decode($job['contractType'],true);
        $job['currency']=json_decode($job['currency'],true);
        $job['skills']=json_decode($job['skills'],true);
        return $job;
      }*/


    /*  public function addJob($request, $response, $args){
        try{
          $getData = json_decode($request->getBody(),true);

          $this->throwIfNone( $getData['keywords']);
          $this->throwIfNone( $getData['contractType']);
          $this->throwIfNone( $getData['skills']);

          $dlist = json_decode($_SESSION['creds']['dataList'],true);

          $ref = Jobdesc::create([
                            'title' => $getData['title'],
                            'exp' => json_encode($getData['exp'],true),
                            'fund' => json_encode($getData['fund'],true),
                            'relocation' => $getData['relocation'],
                            'remote' => $getData['remote'],
                            'regdate' => date("Y-m-d H:i:s",time()),
                            'keywords' => join(",",$getData['keywords']),
                            'travelPercentage' => $getData['travelPercentage'],
                            'remotePercentage' => $getData['remotePercentage'],
                            'relocationPackage' => $getData['relocationPackage'],
                            'projectId' => $getData['projectId'],
                            'companyId' => $getData['companyId'],
                          'currency'=>json_encode($getData['currency'],true),
                            'contractType'=>json_encode($getData['contractType'],true),
                            'other' => "",
                            'location' => $getData['location'],
                            'description' =>$getData['description'],
                            'posterId' => urldecode($dlist[1]['EMAIL']),
                            'skills' => json_encode($getData['skills'],true),
                            'duration' => $getData['duration']
                            ]);

          $dat='prizm';
          $nuhash = $this->iwahash($dat,"JOBID",$ref['id']);
          $nuhash = $this->iwahash($nuhash,"JOBTITLE",$ref['title']);
          $nuhash = $this->iwahash($nuhash,"KEYWORDS",$ref['keywords']);
          $nuhash = $this->iwahash($nuhash,"POSTERID",$ref['posterId']);
          $nuhash = $this->iwahash($nuhash,"REGDATE",$ref['regdate']);

          $ref['hash']  = $nuhash;
          $ref->update();

          /* //Add new job weight */
    /* //TODO: Refator this into function { */
    /* $weighingKeywords = $getData['keywords']; //Weighing keywrods need to be stored as json
    $command = 'sudo /usr/share/nginx/'.env('AI_PATH').'/resources/pythonapis/match/API/run.py '.join(",",$weighingKeywords);
    $returned = shell_exec($command) or die("Failed to call AI with ".$command);
    $weights = json_decode($returned); //get array of predictions

    $weight = Jobweight::create(['jobid'=>$ref['id'],
                       'aone'=>$weights->predictions[0],
                       'atwo'=>$weights->predictions[1],
                       'athree'=>$weights->predictions[2],
                       'afour'=>$weights->predictions[3],
                       'afive'=>$weights->predictions[4],
                       'asix'=>$weights->predictions[5],
                       'aseven'=>$weights->predictions[6],
                       'aeight'=>$weights->predictions[7],
                       'anine'=>$weights->predictions[8],
                       'aten'=>$weights->predictions[9],
                       'aeleven'=>$weights->predictions[10],
                       'keywords'=>json_encode($weighingKeywords,true)
                       ]);

    $ret = $this->returnJob($ref);

    return $response->withJson(array('message'=>"Successfully added job",
                           'status' => "success",
                           'job' => $ret,
                           'jobweight' => $weight));

  }catch (Exception $e){
    return json_encode($e);
  }
}*/

    /*  public function updateJob($request, $response, $args){
        try{
          $getData = $request->getParams();
          $cid = $args['id'];


          $jobdesc = Jobdesc::find($cid);

          $dat='prizm';
          $nuhash = $this->iwahash($dat,"JOBID",$jobdesc->id);
          $nuhash = $this->iwahash($nuhash,"JOBTITLE",$jobdesc->title);
          $nuhash = $this->iwahash($nuhash,"KEYWORDS",$jobdesc->keywords);
          $nuhash = $this->iwahash($nuhash,"POSTERID",$jobdesc->posterid);
          //$nuhash = $this->iwahash($nuhash,"REGDATE",$jobdesc->regdate);

          DB::table('jobs')->where('id', '=', $jobdesc->id)->update([
                       'title' => $getData['title'],
                       'exp' => json_encode($getData['exp'], true),
                       'fund' => json_encode($getData['fund'], true),
                       'relocation' => $getData['relocation'],
                       'remote' => $getData['remote'],
                       'regdate' => date("Y-m-d H:i:s",time()),
                       'keywords' => join(",", json_decode($getData['keywords'], true)),
                       'travelPercentage' => $getData['travelPercentage'],
                       'remotePercentage' => $getData['remotePercentage'],
                       'relocationPackage' => $getData['relocationPackage'],
                       'projectId' => $getData['projectId'],
                       'companyId' => $getData['companyId'],
                       'currency'=> json_encode($getData['currency'], true),
                       'contractType'=> json_encode($getData['contractType'], true),
                       'other' => "",
                       'skills' => json_encode($getData['skills'], true),
                       'location' => $getData['location'],
                       //TODO: make Janek pass me some fucking Markdown
                       'description' => strip_tags($getData['description']),
                       'duration' => $getData['duration']
                       ]);

          /* //TODO: Refator this into function { */
    /*$weighingKeywords = $getData['keywords']; //take the must have keywords
    $command = 'sudo /usr/share/nginx/'.env('AI_PATH').'/resources/pythonapis/match/API/run.py '.$weighingKeywords;
    $returned = shell_exec($command) or die("Failed to call AI with ".$command);
    $weights = json_decode($returned); //get array of predictions

    $weight = Jobweight::create(['jobid'=>$cid,
                 'aone'=>$weights->predictions[0],
                 'atwo'=>$weights->predictions[1],
                 'athree'=>$weights->predictions[2],
                 'afour'=>$weights->predictions[3],
                 'afive'=>$weights->predictions[4],
                 'asix'=>$weights->predictions[5],
                 'aseven'=>$weights->predictions[6],
                 'aeight'=>$weights->predictions[7],
                 'anine'=>$weights->predictions[8],
                 'aten'=>$weights->predictions[9],
                 'aeleven'=>$weights->predictions[10],
                  'keywords'=> json_encode($weighingKeywords, true)
                 ]);

    return $response->withJson(array('message'=>"Successfully updated job",
                     'status' => "success",
                     'jobweight' => $weight));

  }catch (Exception $e){
    return json_encode($e);
  }
}*/



    function returnProject($aproj){
        $project = $aproj;
        $project['contractType']=json_decode($project['contractType'],true);
        $project['breakdown']=json_decode($project['breakdown'],true);
        $project['stack']= json_decode($project['stack'],true);
        $project['methodology']= json_decode($project['methodology'],true);
        $project['projectType']= json_decode($project['projectType'],true);
        $project['workload']= json_decode($project['workload'],true);
        $project['requiredSkills']= json_decode($project['requiredSkills'],true);
        $project['perks']= json_decode($project['perks'],true);
        return $project;
    }


    /*  public function addProject($request, $response, $args){
        try{
          //TODO: Auth middleware based on plancking should kick in for these requests
          // https://discourse.slimframework.com/t/slim-3-how-to-read-post-variables-from-body/766/8
          // Had issues with Jan posting
          $getData = json_decode($request->getBody(),true);

          $this->throwIfNone($getData['data']['companyId']);
          //      $this->throwIfNone($getData['data']['requiredSkills']);
          $this->throwIfNone($getData['data']['breakdown']);
          //      $this->throwIfNone($getData['data']['workload']);

          $newProject = Project::create(['staff'=> $getData['data']['staff'] ,
                         'name'=>$getData['data']['name'],
                               'description'=> $getData['data']['description'] ,
                               'posterId'=> $getData['data']['posterId'],
                         'contractType'=>json_encode($getData['data']['contractType'],true),
                                 'breakdown'=>json_encode($getData['data']['breakdown'],true),
                               'stack'=> json_encode($getData['data']['stack'],true),
                               'methodology'=> json_encode($getData['data']['methodology'],true),
                               'logo'=> json_encode($getData['data']['logo'],true),
                               'projectType'=> json_encode($getData['data']['projectType'],true),
                               'workload'=> json_encode($getData['data']['workload'],true),
                               'requiredSkills'=> json_encode($getData['data']['requiredSkills'],true),
                               'perks'=> json_encode($getData['data']['perks'],true),
                               'stage'=> $getData['data']['stage'],
                               'companyId'=> $getData['data']['companyId']
                               ]);

          return $response->withJson(array('message'=>"Successfully added project for company #".$getData['data']['companyId'],
                           'status' => "success",
                           'project'=>  $this->returnProject($newProject)));

        }catch (Exception $e){
          return json_encode($e);
        }
      }*/


    public function addCompany($request, $response, $args){
        try{
            //TODO: Auth middleware based on plancking should kick in for these requests

            // https://discourse.slimframework.com/t/slim-3-how-to-read-post-variables-from-body/766/8
            // Had issues with Jan posting
            $getData = json_decode($request->getBody(),true);

            $this->throwIfNone($getData['data']['name']);

            $uid = User::where('unique_id',$getData['data']['id'])->first();

            $newCompany = Company::firstOrCreate(['name'=> $getData['data']['name']],['name'=> $getData['data']['name'] ,
                'description'=> $getData['data']['description'] ,
                'posterId'=> $uid->id
            ]);

            $data = ['status' => "success",
            'company' => $newCompany,
            'message' => "Company successfully added"];

            return $this->jsonResponse($response, $data);
            
        }catch (Exception $e){
            return json_encode($e);
        }
    }

    public function updateCompany($request, $response, $args){
        try{
            //TODO: Auth middleware based on plancking should kick in for these requests

            // https://discourse.slimframework.com/t/slim-3-how-to-read-post-variables-from-body/766/8
            // Had issues with Jan posting
            $getData = json_decode($request->getBody(),true);
            $cid = $args['id'];

            $this->throwIfNone($getData['data']['posterId']);

            $company = Company::where('id',$cid);

            $newCompany = $company->update(['id'=>$cid,
                'name'=> $getData['data']['companyName'] ,
                'description'=> $getData['data']['description'] ,
                'posterId'=> $getData['data']['posterId']
            ]);

            return $response->withJson(array('message'=>"Successfully updated company ".$company->name,
                'status' => "success"));

        }catch (Exception $e){
            return json_encode($e);
        }
    }


    /*  public function updateProject($request, $response, $args){
        try{
          //TODO: Auth middleware based on plancking should kick in for these requests

          // https://discourse.slimframework.com/t/slim-3-how-to-read-post-variables-from-body/766/8
          // Had issues with Jan posting
          $getData = json_decode($request->getBody(),true);
          $cid = $args['id'];

          $this->throwIfNone($getData['data']['companyId']);
          $this->throwIfNone($getData['data']['posterId']);

          $project = Project::where('id',$cid);

          $newProject = $project->update(['staff'=> $getData['data']['staff'] ,
                          'description'=> $getData['data']['description'] ,
                          'contractType'=>$getData['data']['contractType'],
                          'name'=>$getData['data']['name'],
                          'posterId'=> $getData['data']['posterId'],
                          'stack'=> json_encode($getData['data']['stack'],true),
                          'methodology'=> json_encode($getData['data']['methodology'],true),
                          'logo'=> json_encode($getData['data']['logo'],true),
                          'projectType'=> $getData['data']['methodology'],
                          'workload'=> json_encode($getData['data']['workload'],true),
                          'requiredSkills'=> json_encode($getData['data']['requiredSkills'],true),
                          'perks'=> json_encode($getData['data']['perks'],true),
                          'stage'=> $getData['data']['stage'],
                          'companyId'=> $getData['data']['companyId']
                          ]);

          return $response->withJson(array('message'=>"Successfully modified project for company #".$getData['data']['companyId'],
                           'status' => "success",
                           'project'=> $this->returnProject($newProject)));



        }catch (Exception $e){
          return json_encode($e);
        }
      }*/

    public function deleteCompany($request, $response, $args){
        try{
            $getData = json_decode($request->getBody(),true);
            $cid = $args['id'];

            $company = Company::where('id',$cid)->delete();

            return $response->withJson(array('message'=>"Successfully removed company:",
                'companyId'=> $company,
                'status' => "success"));

        }catch (Exception $e){
            return json_encode($e);
        }
    }

    public function getCompany($request, $response, $args){
        try{
            $cid = $args['id'];
            $company = Company::where('id',$cid)->get();
            return $response->withJson($company);
        }catch (Exception $e){
            return json_encode($e);
        }
    }

    public function getCompanies($request, $response, $args){
        try{
            //id is unique_id;
            $uid = $args['id'];
            $auid = User::where('unique_id', $uid)->first();

            if(isset($auid)){
                $companies = Company::where('posterId', $auid->id)->get();
            }else{
                //Need to inform Jan that he needs to add a param or argument
                //To obtain the companies that are required by only 'this' user
                $companies = Company::all();
            }

            $data = ['status' => "success",
            'companies' => $companies,
            'message' => "Getting experience for user"];

            return $this->jsonResponse($response, $data);
        }catch (Exception $e){
            return json_encode($e);
        }
    }

    /*  public function getJob($request, $response, $args){
        try{
          $cid = $args['id'];
          $jobdesc = Jobdesc::where('id',$cid)->get();
          $retjob =  $this->returnJob($jobdesc);
          return $response->withJson($retjob);
        }catch (Exception $e){
          return json_encode($e);
        }
      }*/


    /*  public function deleteProject($request, $response, $args){
        try{
          //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....

          $getData = json_decode($request->getBody(),true);
          $cid = $args['id'];

          $project = Project::where('id',$cid)->delete();

          return $response->withJson(array('message'=>"Successfully removed project ".$cid,
                           'status' => "success"));

        }catch (Exception $e){
          return json_encode($e);
        }
      }*/


    /*  public function deleteJob($request, $response, $args){
        try{
          //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....
          $cid = $args['id'];
          $project = Jobdesc::where('id',$cid)->delete();
          $delJobW = Jobweight::where('jobid',$cid)->delete();

          return $response->withJson(array('message'=>"Successfully removed project ".$cid,
                           'status' => "success"));
        }catch (Exception $e){
          return json_encode($e);
        }
      }*/

    /*  public function getProjects($request, $response, $args){
        try{
          //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....
          $projects = Project::all();

          $ret = [];

          foreach($projects as $projdesc){
        $ret[] = $this->returnProject($projdesc);
          }

          return $response->withJson($ret);
        }catch (Exception $e){
          return json_encode($e);
        }
      }
      public function getProject($request, $response, $args){
        try{
          //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....
          $project = Project::where('id',$args['id'])->get()[0];

          $aproj = $this->returnProject($project);

          return $response->withJson($aproj);
        }catch (Exception $e){
          return json_encode($e);
        }
      }

      public function getCompanyProjects($request, $response, $args){
        try{
          //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....
          //TODO - fix this
          $cid = $args['company'];
          $projects = Project::where('company_id', $cid)->get();

          $ret=[];

    /*      foreach($projects as $desc){
        $ret[] = $this->returnProject($desc);
          }*/
    /*
         return $response->withJson($ret);
       }catch (Exception $e){
         return json_encode($e);
       }
     }*/


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

    public function getBuildProfile($request,$response)
    {
        //Get the vue booter for profilebuild component
        return $this->view->render($response,'app-boot.twig', array('state'=>'profilebuild'));
    }

    public function jobsToStrongUid($request,$resonse,$args){
        $uid = $args['uid'];
        $userweight = json_decode(UserWeight::where('userid', $uid)->orderBy('created_at','desc')->get(),true)[0];

        $matchedJobs = $this->matchjob($request, $resonse, $args);

    }

    public function profilesToStrongJid($request,$resonse,$args){
        $jid = $args['jid'];

    }

}
