<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\ConfigCachingAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use PHPUnit\Framework\Attributes\Test;

class ConfigCachingAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(ConfigCachingAnalyzer::class, $app);
    }

    #[Test]
    public function detects_cached_config_in_local()
    {
        $this->app->config->set('app.env', 'local');

        // Set the config_loaded_from_cache binding to simulate cached config
        $this->app->instance('config_loaded_from_cache', true);

        $this->runEnlightn();

        $this->assertFailed(ConfigCachingAnalyzer::class);
    }

    #[Test]
    public function detects_non_cached_config_in_production()
    {
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertFailed(ConfigCachingAnalyzer::class);
    }

    #[Test]
    public function passes_cached_config_in_production()
    {
        $this->app->config->set('app.env', 'production');

        // Set the config_loaded_from_cache binding to simulate cached config
        $this->app->instance('config_loaded_from_cache', true);

        $this->runEnlightn();

        $this->assertPassed(ConfigCachingAnalyzer::class);
    }

    #[Test]
    public function passes_non_cached_config_in_local()
    {
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertPassed(ConfigCachingAnalyzer::class);
    }
}
