<?php
declare(strict_types=1);

namespace Tests\Unit;

use Codeception\Test\Unit;
use PHPUnit\Framework\TestCase;

final class JobServiceTest extends Unit
{
    public function testCreateJob() {
        $mockRepo = $this->createMock(JobRepository::class);
        $mockRepo->method('save')->willReturn(new Jobdesc());

        $service = new JobService($mockRepo);
        $job = $service->createJob(['title' => 'Test Job'], 1);

        $this->assertInstanceOf(Jobdesc::class, $job);
    }
}
