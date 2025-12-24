<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\EnvExampleAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\EnvStub;
use PHPUnit\Framework\Attributes\Test;

class EnvExampleAnalyzerTest extends AnalyzerTestCase
{
    protected $files;

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(EnvExampleAnalyzer::class, $app);
    }

    #[Test]
    public function detects_missing_env_variables()
    {
        $this->app->setBasePath(dirname($this->getClassStubPath(EnvStub::class)));

        $this->runEnlightn();

        $this->assertFailed(EnvExampleAnalyzer::class);
        $this->assertErrorMessageContains(EnvExampleAnalyzer::class, 'KEY_FOUR');
        $this->assertErrorMessageDoesNotContain(EnvExampleAnalyzer::class, 'KEY_ONE');
        $this->assertErrorMessageDoesNotContain(EnvExampleAnalyzer::class, 'KEY_TWO');
        $this->assertErrorMessageDoesNotContain(EnvExampleAnalyzer::class, 'KEY_THREE');
    }
}
