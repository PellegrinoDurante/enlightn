<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\RouteCachingAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use PHPUnit\Framework\Attributes\Test;

class RouteCachingAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(RouteCachingAnalyzer::class, $app);
    }

    #[Test]
    public function detects_cached_routes_in_local()
    {
        $this->app->config->set('app.env', 'local');
        
        // Set the routes.cached binding to simulate cached routes
        $this->app->instance('routes.cached', true);

        $this->runEnlightn();

        $this->assertFailed(RouteCachingAnalyzer::class);
    }

    #[Test]
    public function detects_non_cached_routes_in_production()
    {
        $this->app->config->set('app.env', 'production');
        
        $this->runEnlightn();

        $this->assertFailed(RouteCachingAnalyzer::class);
    }

    #[Test]
    public function passes_cached_routes_in_production()
    {
        $this->app->config->set('app.env', 'production');
        
        // Set the routes.cached binding to simulate cached routes
        $this->app->instance('routes.cached', true);

        $this->runEnlightn();

        $this->assertPassed(RouteCachingAnalyzer::class);
    }

    #[Test]
    public function passes_non_cached_routes_in_local()
    {
        $this->app->config->set('app.env', 'local');
        
        $this->runEnlightn();

        $this->assertPassed(RouteCachingAnalyzer::class);
    }
}
