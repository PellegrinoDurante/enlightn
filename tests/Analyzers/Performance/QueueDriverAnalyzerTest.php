<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\QueueDriverAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use PHPUnit\Framework\Attributes\Test;

class QueueDriverAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(QueueDriverAnalyzer::class, $app);
    }

    #[Test]
    public function detects_null_queue_driver()
    {
        $this->app->config->set('queue.default', 'null');

        $this->runEnlightn();

        $this->assertFailedAt(QueueDriverAnalyzer::class, $this->getConfigStubPath('queue'), 16);
    }

    #[Test]
    public function detects_sync_queue_driver()
    {
        $this->app->config->set('queue.default', 'sync');

        $this->runEnlightn();

        $this->assertFailedAt(QueueDriverAnalyzer::class, $this->getConfigStubPath('queue'), 16);
    }

    #[Test]
    public function detects_database_queue_driver_in_non_local_env()
    {
        $this->app->config->set('queue.default', 'database');
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertFailedAt(QueueDriverAnalyzer::class, $this->getConfigStubPath('queue'), 16);
    }

    #[Test]
    public function passes_database_queue_driver_in_local_env()
    {
        $this->app->config->set('queue.default', 'database');
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertPassed(QueueDriverAnalyzer::class);
    }
}
