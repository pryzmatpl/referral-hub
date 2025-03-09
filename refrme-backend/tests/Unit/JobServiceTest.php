<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Job;
use App\Models\JobDesc;
use App\Repositories\JobRepository;
use App\Services\JobClassificationService;
use App\Services\JobService;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\Exception;

final class JobServiceTest extends Unit
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function testCreateJob() {
        $mockRepo = $this->createMock(JobRepository::class);
        $mockRepo->method('save')->willReturn(new Job());

        $mockJobClassificationService = $this->createMock(JobClassificationService::class);
        $mockJobClassificationService->method('classifyJob')->willReturn(["backend"=>0.95, "frontend"=>0.05]);

        $service = new JobService($mockRepo, $mockJobClassificationService);
        $job = $service->createJob(['title' => 'Test Job'], 1);

        $this->assertEquals(["backend"=>0.95, "frontend"=>0.05], $mockJobClassificationService->classifyJob($job->weights));

        $this->assertInstanceOf(Job::class, $job);
    }
}
