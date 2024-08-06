<?php

namespace App\Controllers;

use App\Models\Jobdesc;
use App\Models\Jobweight;
use Nette\Mail\Message;
use PHPUnit\Exception;
use App\Models\Apply;

class JobController extends Controller {

    public function get($request, $response, $args)
    {
        try {
            $params = $request->getParams();
            $like_columns = ['title', 'description', 'project.perks','contract_type','location','company.name'];

            //TODO: This needs redoing
            $params = dotify($params);

            if ( isset($params['q']) ){
                unset($params['q']);
            }

            if ( isset($params['with']) ) {
                $with = explode(',' , $params['with']);
                unset($params['with']);
            }

            $currentPage = $params['page'];

            if ( empty($params) ) { $jobdescs = isset($with) ? Jobdesc::with($with)->get() : Jobdesc::get(); }
            else {
                $logic = 'or';

                if ( isset($params['logic']) ) {
                    $logic = $params['logic'];
                    unset( $params['logic'] );
                }

                if ( isset($params['location']) ) {
                    $params['location'] = urldecode($params['location']);
                }

                $params = sc($params); //Snake case the params

                $jobdescs = Jobdesc::where( function($query) use ($like_columns, $params, $logic) {
                    foreach ($params as $key => $value) {

                        if (in_array($key, $like_columns)) {
                            $operator = 'like';
                            $value = '%' . $value . '%';
                        } else {
                            //Exceptions in query for salary_min
                            if($key == 'salary.min'){
                                $key = 'salary_min';
                                $operator = '>';
                            }else if($key == 'relocation.package'){
                                $key = 'relocation_package';
                                $operator = '=';
                            }else if($key == 'page'){
                                //Do nothing
                                continue;
                            }else{
                                $operator = '=';
                            }
                        }

                        if (strpos($key, '.')) {
                            $key_array = explode('.', $key);
                            $key = $key_array[count($key_array) - 1];
                            unset($key_array[count($key_array) - 1]);
                            $query->whereHas(implode('.', $key_array), function($query) use($key, $value, $operator, $logic) {
                                if (in_array($key, ['keywords', 'skills'])) { // array type value
                                    foreach (explode(',', $value) as $subvalue) {
                                        $logic == 'or' ? $query->orWhere($key, 'like', '%' . $subvalue . '%') : $query->where($key, 'like', '%' . $subvalue . '%');
                                    }
                                } else { // simple value
                                    $logic == 'or' ? $query->orWhere($key, $operator, $value) : $query->where($key, $operator, $value);
                                }
                            });
                        } else {
                            if (in_array($key, ['keywords', 'skills'])) { // array type value
                                foreach (explode(',', $value) as $subvalue) {
                                    $logic == 'or' ? $query->orWhere($key, 'like', '%' . $subvalue . '%') : $query->where($key, 'like', '%' . $subvalue . '%');
                                }
                            } else { // simple value
                                $logic == 'or' ? $query->orWhere($key, $operator, $value) : $query->where($key, $operator, $value);
                            }
                        }

                    }
                });

                $jobdescs = isset($with) ? $jobdescs->with($with) : $jobdescs;
            }

            $pageSize = 15;
            $noOfPages = ceil($jobdescs->count()/$pageSize) - 1;
            $aCount = $jobdescs->count();

            if( !isset($currentPage) ){
                $results = $jobdescs->all();

                foreach($results as &$job){
                    $job->keywords = explode(',' , $job->keywords);
                }

                $aresults=['data'=>$results,
                    'pages'=>$noOfPages,
                    'count'=>$aCount];

                return $response->withJson(cc($aresults));
            }else{
                $results = $jobdescs->get();

                foreach($results as &$job){
                    $job->keywords = explode(',' , $job->keywords);
                }

                $results = array_slice($results->toArray(),$currentPage*$pageSize, $pageSize);

                $aresults=['data'=>$results,
                    'pages'=>$noOfPages,
                    'current'=>$currentPage,
                    'count'=>$aCount];

                return $response->withJson( cc($aresults) );
            }
            // Temp due to keywords being comma separated.

        } catch (\Exception $e) {
            return json_encode($e);
        }

    }

    public function add($request, $response, $args) {
        try {
            $data = sc(json_decode($request->getBody(), true));

            $job = new Jobdesc();
            $job->user_id = $_SESSION['user']->id;
            ( !is_null($data['title']) ) ?  $job->title = $data['title'] : exit("No Title");
            $job->exp = $data['exp'];
            $job->fund = $data['fund'];
            $job->salary_min = $data['fund'][0];
            $job->salary_max = $data['fund'][1];
            $job->relocation = $data['relocation'];
            $job->remote = $data['remote'];
            $job->keywords = implode(',', $data['keywords']);
            $job->travel_percentage = $data['travel_percentage'];
            $job->remote_percentage = $data['remote_percentage'];
            $job->relocation_package = $data['relocation_package'];
            $job->project_id = $data['project_id'];
            $job->company_id = $data['company_id'];
            $job->currency = $data['currency'];
            $job->contract_type = $data['contract_type'];
            $job->other = "";
            $job->location = $data['location'];
            $job->description = $data['description'];
            $job->skills = $data['skills'];
            ( !is_null($data['skills_nice']) ) ? $job->skills_nice = $data['skills_nice'] : exit("No skills_nice");
            ( !is_null($data['frameworks_must']) ) ?  $job->frameworks_must = $data['frameworks_must'] : exit("No frameworks");
            ( !is_null($data['frameworks_nice']) ) ? $job->frameworks_nice = $data['frameworks_nice'] : exit("No Nice frameworks");
            ( !is_null($data['methodologies_must'])) ? $job->methodologies_must = $data['methodologies_must'] : exit("No methodologies");
            ( !is_null($data['methodologies_nice'])) ? $job->methodologies_nice = $data['methodologies_nice'] : exit("No nice methodologies");
            $job->duration = (int) $data['duration'];
            $job->save();

            $weight = $this->weight($job->id, $job->keywords);

            return $response->withJson(array('message' => "Successfully added job",
                'status' => "success",
                'job' => $job,
                'jobweight' => $weight));

        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function update($request, $response, $args) {
        try {
            $data = sc($request->getParams());

            $job = Jobdesc::find($args['id']);

            if (is_null($job)) throw new \Exception('Job not found.');
            if (!$_SESSION['user']->is_admin && $_SESSION['user']->id != $job->user_id) throw new \Exception('Permission denied.');

            if (isset($data['title'])) $job->title = $data['title'];
            if (isset($data['exp'])) $job->exp = $data['exp'];
            if (isset($data['fund'])) $job->fund = $data['fund'];
            if (isset($data['salary_min'])) $job->fund = $data['salary_min'];
            if (isset($data['salary_max'])) $job->fund = $data['salary_max'];
            if (isset($data['relocation'])) $job->relocation = $data['relocation'];
            if (isset($data['remote'])) $job->remote = $data['remote'];
            if (isset($data['keywords'])) $job->keywords = join(',', $data['keywords']);
            if (isset($data['travel_percentage'])) $job->travel_percentage = $data['travel_percentage'];
            if (isset($data['remote_percentage'])) $job->remote_percentage = $data['remote_percentage'];
            if (isset($data['relocation_package'])) $job->travel_percentage = $data['relocation_package'];
            if (isset($data['project_id'])) $job->project_id = $data['project_id'];
            if (isset($data['company_id'])) $job->company_id = $data['company_id'];
            if (isset($data['currency'])) $job->currency = $data['currency'];
            if (isset($data['contract_type'])) $job->contract_type = $data['contract_type'];
            if (isset($data['other'])) $job->other = '';
            if (isset($data['skills'])) $job->skills = $data['skills'];
            if (isset($data['skills_nice'])) $job->skills = $data['skills_nice'];
            if (isset($data['frameworks'])) $job->skills = $data['frameworks'];
            if (isset($data['frameworks_nice'])) $job->skills = $data['frameworks_nice'];
            if (isset($data['methodologies'])) $job->skills = $data['methodologies'];
            if (isset($data['methodologies_nice'])) $job->skills = $data['methodologies_nice'];
            if (isset($data['location'])) $job->location = $data['location'];
            if (isset($data['description'])) $job->description = $data['description'];
            if (isset($data['duration'])) $job->duration = $data['duration'];

            $job->save();
            $weight = $this->weight($job->id, $job->keywords);

            return $response->withJson([
                'message'=>"Successfully updated job",
                'status' => "success",
                'jobweight' => $weight
            ]);

        } catch (\Exception $e) {
            return json_encode($e);
        }
    }

    public function delete($request, $response, $args){
        try{
            //TODO If a project does not exist, it will return success. DB integrity, anyone -_-....
            $id = $args['id'];
            $job = Jobdesc::where('id', $id)->delete();
            $job_weights = Jobweight::where('job_id', $id)->delete();

            return $response->withJson([
                'message'=>"Successfully removed project ".$id,
                'status' => "success"
            ]);
        } catch (\Exception $e) {
            return json_encode($e);
        }
    }

    public function apply($request, $response, $args) {
        try {
            $userId = $_SESSION['user']->id;
            $data = $request->getParsedBody();
            $job = Jobdesc::with('user')->where('id', $data['job_id'])->first();
            $umail = urldecode($data['email']);
            if (is_null($job)) throw new \Exception('Job not found.');

            $apply = new Apply();
            $apply->jobs_id = $data['job_id'];
            $apply->users_id = $userId;

            if($apply->save()) {
                $apply->update(['hash' => $this->iwahash($apply->id, "TOKEN", env('TOKEN'))]);

                if($this->sendMailApply($umail, $job)) {
                    return $response->withJson([
                        'state'=>"success",
                        'message' => "You have succesfully applied for ".$job->title
                    ]);
                }
            }

            return $response->withJson([
                'message'=> "There was an error sending your apply",
                'status' => "error",
                'referral'=> cc($apply->toArray())
            ]);
        } catch(Exception $e) {
            return $e;
        }
    }

    private function sendMailApply($umail, $job) {
        $mail = new Message;

        $mail
            ->setFrom(env('MAIL_USERNAME'))
            ->addTo($job->posterId)
            ->addTo($umail)
            ->addBcc(env('OVERWATCH'))
            ->setSubject('Refair.me Match! Here are your next steps for '.$job->title . 'job.')
            ->setHtmlBody(renderEmailTemplate('apply', ['job' => $job]));

        return !$this->mailer->send($mail);
    }

    public function getApply($request, $response, $args) {
        try {
            $email = strip_tags($args['user']);
            $user = User::where('email', $email)->first();

            if(empty($user))
                throw new \Exception('User not found.');

            $applies = Apply::where('users_id', $user->id)
                ->with('User')
                ->with('Job')
                ->get();

            return $response->withJson(cc($applies->toArray()));
        } catch (\Exception $e) {
            return json_encode($e);
        }
    }

    private function weight($job_id, $keywords) {
        $command = 'sudo /usr/share/nginx/' . env('AI_PATH') . '/resources/pythonapis/match/API/run.py ' . $keywords;
        $returned = shell_exec($command) or die("Failed to call AI with " . $command);
        $weights = json_decode($returned); //get array of predictions

        $weight = Jobweight::create(['job_id' => $job_id,
            'aone' => $weights->predictions[0],
            'atwo' => $weights->predictions[1],
            'athree' => $weights->predictions[2],
            'afour' => $weights->predictions[3],
            'afive' => $weights->predictions[4],
            'asix' => $weights->predictions[5],
            'aseven' => $weights->predictions[6],
            'aeight' => $weights->predictions[7],
            'anine' => $weights->predictions[8],
            'aten' => $weights->predictions[9],
            'aeleven' => $weights->predictions[10],
            'keywords' => json_encode($keywords, true)
        ]);

        return $weight;
    }

}