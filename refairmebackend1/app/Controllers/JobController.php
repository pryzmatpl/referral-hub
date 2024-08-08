<?php
namespace App\Controllers;

use App\Services\JobService;
use App\Repositories\JobRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class JobController extends Controller {
    private $jobService;
    private $jobRepository;

    public function __construct(JobService $jobService, JobRepository $jobRepository) {
        $this->jobService = $jobService;
        $this->jobRepository = $jobRepository;
    }

    public function get(Request $request, Response $response, array $args): Response {
        try {
            $params = $request->getQueryParams();
            $jobs = $this->jobService->searchJobs($params);

            return $response->withJson($jobs);
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
    }

    public function add(Request $request, Response $response, array $args): Response {
        try {
            $data = json_decode($request->getBody(), true);
            $job = $this->jobService->createJob($data, $_SESSION['user']->id);

            return $response->withJson(['message' => 'Successfully added job', 'status' => 'success', 'job' => $job]);
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Response $response, array $args): Response {
        try {
            $data = $request->getParsedBody();
            $job = $this->jobService->updateJob($args['id'], $data, $_SESSION['user']);

            return $response->withJson(['message' => 'Successfully updated job', 'status' => 'success', 'job' => $job]);
        } catch (\Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
    }
}
