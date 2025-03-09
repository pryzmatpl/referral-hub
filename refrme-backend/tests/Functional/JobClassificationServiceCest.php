<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 09.03.2025, 13:08
 * JobClassificationServiceCest.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace Tests\Functional;

use App\Services\JobClassificationService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

class JobClassificationServiceCest extends TestCase
{
    /**
     * @var string
     */
    private $tempDir;

    protected function setUp(): void
    {
        // Create a temporary directory for our fake script
        $this->tempDir = sys_get_temp_dir() . '/job_classification_test_' . uniqid();
        mkdir($this->tempDir, 0777, true);
    }

    protected function tearDown(): void
    {
        // Clean up the temporary directory recursively
        $this->deleteDirectory($this->tempDir);
    }

    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    public function testClassifyJobSuccess()
    {
        // Create a dummy run.py that outputs valid JSON.
        // Note: We use PHP as the interpreter (passed as $pythonPath) so that
        // "php run.py" will execute our dummy script.
        $runPy = $this->tempDir . '/run.py';
        $scriptContent = <<<'PHP'
<?php
echo json_encode([
    'backend' => '0.95',
    'frontend' => 0.05
]);
PHP;
        file_put_contents($runPy, $scriptContent);
        chmod($runPy, 0755);

        // Create a logger mock and expect an info log on success.
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->once())
            ->method('info')
            ->with(
                $this->equalTo('Job classified successfully'),
                $this->arrayHasKey('category')
            );

        // Instantiate the service using 'php' as the interpreter.
        $service = new JobClassificationService($logger, 'php', $this->tempDir);

        $result = $service->classifyJob(['developer']);

        $this->assertArrayHasKey('backend', $result);
        $this->assertEquals(0.95, $result['backend']);
        $this->assertArrayHasKey('frontend', $result);
        $this->assertEquals(0.05, $result['frontend']);
    }

    public function testClassifyJobProcessFailure()
    {
        // Create a dummy run.py that fails (exit with a nonzero status).
        $runPy = $this->tempDir . '/run.py';
        $scriptContent = <<<'PHP'
<?php
exit(1);
PHP;
        file_put_contents($runPy, $scriptContent);
        chmod($runPy, 0755);

        // Create a logger mock and expect an error log.
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Job classification failed'));

        $service = new JobClassificationService($logger, 'php', $this->tempDir);

        $this->expectException(ProcessFailedException::class);

        // This call should throw a ProcessFailedException because the process fails.
        $service->classifyJob(['developer']);
    }

    public function testClassifyJobInvalidJson()
    {
        // Create a dummy run.py that outputs an invalid JSON string.
        $runPy = $this->tempDir . '/run.py';
        $scriptContent = <<<'PHP'
<?php
echo 'not json';
PHP;
        file_put_contents($runPy, $scriptContent);
        chmod($runPy, 0755);

        // Create a logger mock and expect an error log.
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Job classification failed'));

        $service = new JobClassificationService($logger, 'php', $this->tempDir);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid JSON response from classifier');

        // This call should throw an exception due to JSON parsing error.
        $service->classifyJob(['developer']);
    }
}
