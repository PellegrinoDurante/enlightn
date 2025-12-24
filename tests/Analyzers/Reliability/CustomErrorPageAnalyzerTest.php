<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\CustomErrorPageAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithMiddleware;
use PHPUnit\Framework\Attributes\Test;

class CustomErrorPageAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithMiddleware;

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(CustomErrorPageAnalyzer::class, $app);
    }

    #[Test]
    public function skipped_for_stateless_apps()
    {
        $this->runEnlightn();

        $this->assertSkipped(CustomErrorPageAnalyzer::class);
    }

    #[Test]
    public function detects_no_custom_error_pages()
    {
        $this->registerStatefulGlobalMiddleware();

        $this->runEnlightn();

        $this->assertFailed(CustomErrorPageAnalyzer::class);
    }

    #[Test]
    public function detects_custom_error_pages()
    {
        $this->registerStatefulGlobalMiddleware();
        $this->app->config->set('view.paths', [$this->getViewStubPath()]);

        $this->runEnlightn();

        $this->assertPassed(CustomErrorPageAnalyzer::class);
    }

    #[Test]
    public function detects_custom_error_namespace()
    {
        $this->registerStatefulGlobalMiddleware();
        $this->app['view']->replaceNamespace('errors', $this->getViewStubPath().DIRECTORY_SEPARATOR.'errors');

        $this->runEnlightn();

        $this->assertPassed(CustomErrorPageAnalyzer::class);
    }
}
