<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\EnvVariableAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\EnvStub;
use PHPUnit\Framework\Attributes\Test;

class EnvVariableAnalyzerTest extends AnalyzerTestCase
{
    protected $files;

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(EnvVariableAnalyzer::class, $app);
    }

    #[Test]
    public function detects_missing_env_variables()
    {
        $this->app->setBasePath(dirname($this->getClassStubPath(EnvStub::class)));

        $this->runEnlightn();

        $this->assertFailed(EnvVariableAnalyzer::class);
        $this->assertErrorMessageContains(EnvVariableAnalyzer::class, 'KEY_TWO');
        $this->assertErrorMessageDoesNotContain(EnvVariableAnalyzer::class, 'KEY_ONE');
        $this->assertErrorMessageDoesNotContain(EnvVariableAnalyzer::class, 'KEY_THREE');
        $this->assertErrorMessageDoesNotContain(EnvVariableAnalyzer::class, 'KEY_FOUR');
    }
}
