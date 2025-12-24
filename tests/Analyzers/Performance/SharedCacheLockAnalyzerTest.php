<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\SharedCacheLockAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\SharedCacheLockStub;
use PHPUnit\Framework\Attributes\Test;

class SharedCacheLockAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(SharedCacheLockAnalyzer::class, $app);

        $app->config->set('cache.default', 'redis');
        $app->config->set('cache.stores.redis.lock_connection', null);
    }

    #[Test]
    public function detects_cache_lock_method()
    {
        $this->setBasePathFrom(SharedCacheLockStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(SharedCacheLockAnalyzer::class, $this->getClassStubPath(SharedCacheLockStub::class), 11);
        $this->assertFailedAt(SharedCacheLockAnalyzer::class, $this->getClassStubPath(SharedCacheLockStub::class), 16);
        $this->assertHasErrors(SharedCacheLockAnalyzer::class, 2);
    }

    #[Test]
    public function passes_with_separate_lock_connection()
    {
        $this->app->config->set('cache.stores.redis.lock_connection', 'default');
        $this->setBasePathFrom(SharedCacheLockStub::class);

        $this->runEnlightn();

        $this->assertPassed(SharedCacheLockAnalyzer::class);
    }

    #[Test]
    public function passes_with_no_cache_lock_method()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(SharedCacheLockAnalyzer::class);
    }
}
