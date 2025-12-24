<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\EnvCallAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\EnvStub;
use PHPUnit\Framework\Attributes\Test;

class EnvCallAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(EnvCallAnalyzer::class, $app);
    }

    #[Test]
    public function detects_env_function_call()
    {
        $this->setBasePathFrom(EnvStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(EnvCallAnalyzer::class, $this->getClassStubPath(EnvStub::class), 9);
    }

    #[Test]
    public function ignores_errors()
    {
        $this->setBasePathFrom(EnvStub::class);
        $this->app->config->set('enlightn.ignore_errors', [EnvCallAnalyzer::class => [
            [
                'path' => $this->getClassStubPath(EnvStub::class),
                'details' => 'Function env called.',
            ],
        ]]);

        $this->runEnlightn();

        $this->assertPassed(EnvCallAnalyzer::class);
    }

    #[Test]
    public function passes_with_no_env_call()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(EnvCallAnalyzer::class);
    }
}
