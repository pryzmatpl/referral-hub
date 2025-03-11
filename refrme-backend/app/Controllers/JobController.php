<?php
namespace App\Controllers;

use App\Services\JobClassificationService;
use App\Services\JobService;
use App\Repositories\JobRepository;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class JobController extends Controller {
    private JobService $jobService;
    private JobRepository $jobRepository;
    private JobClassificationService $jobClassificationService;

    public function __construct(
        JobService $jobService,
        JobRepository $jobRepository,
        JobClassificationService $jobClassificationService
    ) {
        $this->jobService = $jobService;
        $this->jobRepository = $jobRepository;
        $this->jobClassificationService=$jobClassificationService;
    }

    public function get(Request $request, Response $response, array $args): Response {
        try {
            $params = $request->getQueryParams();
            if (isset($params['logic']) && $params['logic'] == 'all') {
                $jobs = $this->jobService->all();
            } else if(isset($params['id'])) {
                $jobs = $this->jobService->findById([$params['id']]);
            } else  {
                $jobs = $this->jobService->searchJobs($params);
            } 

            $response->getBody()->write(json_encode($jobs));

            return $response;
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response;
        }
    }

    public function add(Request $request, Response $response, array $args): Response {
        try {
            $data = json_decode($request->getBody(), true);
            $job = $this->jobRepository->createJob($data);

            $res = [
                'status' => "success",
                'job' => $job,
                'message' => "Successfully added job"
            ];

            return $this->jsonResponse($response, $res);

        } catch (\Exception $e) {
            return $this->jsonResponse($response, data: ['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Response $response, array $args): Response {
        try {
            $data = $request->getParsedBody();
            $job = $this->jobRepository->updateJobWeights($args['id'], $data, $_SESSION['user']);

            return $response->withJson(['message' => 'Successfully updated job', 'status' => 'success', 'job' => $job]);
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
    }

    public function apply(Request $request, Response $response): Response {
        try {
            $req = json_decode($request->getBody(), true);
            $user = User::where('unique_id', $req['unique_id'])->first();

            $appliedJobs = isset($user->jobs_applied) ? $user->jobs_applied : [];

            $jobObject = [
                'job_id' => $req['job_id'],
                'created_at' => date('Y-m-d H:i:s')  // Current date and time
            ];

            $appliedJobs[] = $jobObject;
            $user->jobs_applied = $appliedJobs;
            $user->save();

            $response->getBody()->write(json_encode($user));
            return $response;
        }
        catch (\Exception $e) {

        }
    }

    public function getApply(Request $request, Response $response, array $args): Response {
        try {
            $uid = $args['user'];
            $userJobs = User::where('unique_id', $uid)->value('jobs_applied');



            if (isset($userJobs)) {
                $userJobsIds = array_map(function ($job) {
                    return $job['job_id'];
                }, $userJobs);
                $jobs = $this->jobService->findById($userJobsIds);
                $response->getBody()->write(json_encode($jobs));
            } else {
                $response->getBody()->write(json_encode([]));
            }

            return $response;
        }
        catch (\Exception $e) {
            return $response->withJson(['error'=> $e->getMessage()], 500);
        }
    }
}
