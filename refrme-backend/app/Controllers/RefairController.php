<?php
namespace App\Controllers;
use Exception;
use Nette\Mail\Message;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\UserExperience;
use App\Models\Company;
use App\Models\File;
use App\Models\Referral;
use App\Models\JobDesc;
use App\Models\JobWeight;
use App\Models\UserWeight;
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
        return $response->withHeader('Content-Type', 'application/json');
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

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function classify(Request $request, Response $response, array $args): Response{
        try{
            $queryAll = $request->getQueryParams();
            $query = urldecode($queryAll['eval']);

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

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return Response
     */
    public function storeProfile($request, $response, $args) : Response
    {
        try {
            $data = $request->getParsedBody();
            $params = $data['params'];

            // Retrieve the candidate's weights array and unique identifier from the request.
            $weights = $params['weights'];  // Expected as an array from the classifier
            $uid = $params['unique_id'];

            // Fetch the user record using the unique id
            $auser = User::where('unique_id', $uid)->first();
            if (!$auser) {
                throw new Exception("User not found");
            }

            // Update basic user details and additional profile data.
            $auser->first_name         = $params['firstname'] ?? $auser->first_name;
            $auser->last_name          = $params['lastname'] ?? $auser->last_name;
            $auser->skills             = $params['skills'] ?? $auser->skills;
            $auser->skills_nice        = $params['skills_nice'] ?? $auser->skills_nice;
            $auser->frameworks_must    = $params['frameworks_must'] ?? $auser->frameworks_must;
            $auser->frameworks_nice    = $params['frameworks_nice'] ?? $auser->frameworks_nice;
            $auser->methodologies_must = $params['methodologies_must'] ?? $auser->methodologies_must;
            $auser->methodologies_nice = $params['methodologies_nice'] ?? $auser->methodologies_nice;
            $auser->scheduling         = $params['availability'] ?? $auser->scheduling;
            $auser->salary_expectation = $params['expectedSalary'] ?? $auser->salary_expectation;
            $auser->notice_period      = $params['noticePeriod'] ?? $auser->notice_period;
            $auser->exp                = $params['exp'] ?? $auser->exp;
            $auser->save();

            // Update or create the user's weight record using the new single "weights" JSON column.
            $uweight = UserWeight::updateOrCreate(
                ['userid' => $auser->id],
                [
                    'weights'  => $weights,
                    'keywords' => $params['keywords'] ?? null,
                ]
            );

            // Update or create the user's description data.
            $udesc = UserDescription::updateOrCreate(
                ['user_id' => $auser->id],
                [
                    'keywords'        => $params['keywords'] ?? null,
                    'skills'          => $params['skills'] ?? null,
                    'notice_period'   => $params['noticePeriod'] ?? null,
                    'availability'    => $params['availability'] ?? null,
                    'expected_salary' => $params['expectedSalary'] ?? null,
                    'job_status'      => $params['jobStatus'] ?? null,
                ]
            );

            // Return a JSON response indicating success.
            $response->getBody()->write(json_encode([
                'status'  => "success",
                'message' => "Successfully updated profile for user " . $auser->id
            ]));
            return $response->withHeader('Content-Type', 'application/json');

        } catch (Exception $e) {
            $response->withStatus(500)->getBody()->write(json_encode([
                'status'  => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ]));
        }
    }


    /**
     * @param Request $request
     * @param Response $response
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
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
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
            if (isset($passedWeightsDecoded[0]['predictions'])) {
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
                    $candidateWeights[] = $aggregatedCandidate[$cat] ?? 0;
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


    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function matchJob(Request $request, Response $response, $args) : Response
    {
        try {
            // Retrieve the job id from route parameters.
            $jobid = $args['id'];

            // Get the job weight record (assumes a cast on the 'weights' column to array in JobWeight model).
            $jobWeightRecord = JobWeight::where('jobid', $jobid)->first();
            if (!$jobWeightRecord) {
                $response->getBody()->write(json_encode([
                    'state'   => 'error',
                    'message' => "Job weight record not found for jobid: $jobid"
                ]));
                return $response;
            }
            $jobWeights = $jobWeightRecord->weights; // Array of classifier weights

            // Retrieve all user weight records.
            // @todo: Analyze how to make this faster
            $userWeightRecords = UserWeight::all();
            $matchedUsers = [];
            $threshold = 0.1; // Threshold for determining a match

            foreach ($userWeightRecords as $userWeight) {
                $candidateWeights = $userWeight->weights;

                // Validate that both jobWeights and candidateWeights are valid arrays of same length.
                if (!is_array($jobWeights) || !is_array($candidateWeights) || count($jobWeights) !== count($candidateWeights)) {
                    continue; // Skip if there is a misconfiguration.
                }

                $matchFound = false;
                // Check each category to see if the candidate's weight is within threshold of the job's weight.
                foreach ($jobWeights as $index => $jobVal) {
                    $candidateVal = $candidateWeights[$index];
                    if ($candidateVal >= $threshold && abs($jobVal - $candidateVal) <= $threshold) {
                        $matchFound = true;
                        break;
                    }
                }

                if ($matchFound) {
                    $matchedUsers[] = [
                        'id'      => $userWeight->userid,
                        'weights' => $candidateWeights,
                        'keywords'=> $userWeight->keywords,
                    ];
                }
            }

            // If no users matched the criteria, return an error message.
            if (empty($matchedUsers)) {
                $response->getBody()->write(json_encode([
                    'state'   => 'error',
                    'message' => "There are no users in the system that fit your criteria"
                ]));
                return $response;
            }

            $response->getBody()->write(json_encode($matchedUsers));
            return  $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'state'   => 'error',
                'message' => $e->getMessage()
            ]));
            return $response->withStatus(500);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function classifyJob(Request $request, Response $response, $args): Response
    {
        try {
            $jobid = $args['jobid'];

            // Retrieve the job weight record using the JobWeight model.
            // The model automatically casts the 'weights' JSON column into an array.
            $jobWeightRecord = JobWeight::where('jobid', $jobid)->first();
            if (!$jobWeightRecord) {
                $response->getBody()->write(json_encode([
                    'state'   => 'error',
                    'message' => "Job weight record not found for jobid: $jobid"
                ]));
                return $response->withStatus(404);
            }

            // Get the weights as an array.
            $jobWeights = $jobWeightRecord->weights;

            // Build the response array.
            $retarr = [
                "results" => ["Result from job eval during job posting"],
                "weights" => $jobWeights
            ];

            $response->getBody()->write(json_encode($retarr));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'state'   => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ]));
            return $response->withStatus(500);
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
        $userweight = json_decode(UserWeight::where('user_id', $uid)->orderBy('created_at','desc')->get(),true)[0];

        $matchedJobs = $this->matchJob($request, $resonse, $args);

    }

    public function profilesToStrongJid($request,$resonse,$args){
        $jid = $args['jid'];

    }

}
