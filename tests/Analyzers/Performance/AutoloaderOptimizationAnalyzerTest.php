<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\AutoloaderOptimizationAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use PHPUnit\Framework\Attributes\Test;

class AutoloaderOptimizationAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(AutoloaderOptimizationAnalyzer::class, $app);
    }

    #[Test]
    public function skips_in_local()
    {
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertSkipped(AutoloaderOptimizationAnalyzer::class);
    }

    #[Test]
    public function detects_non_optimized_autoloader()
    {
        $this->app->config->set('app.env', 'production');

        $this->app->setBasePath(
            realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR)
        );

        $this->runEnlightn();

        $this->assertFailed(AutoloaderOptimizationAnalyzer::class);
    }
}
