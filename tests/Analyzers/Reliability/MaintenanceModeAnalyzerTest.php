<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\MaintenanceModeAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use PHPUnit\Framework\Attributes\Test;

class MaintenanceModeAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(MaintenanceModeAnalyzer::class, $app);
    }

    #[Test]
    public function confirms_is_not_down()
    {
        $this->runEnlightn();

        $this->assertPassed(MaintenanceModeAnalyzer::class);
    }
}
