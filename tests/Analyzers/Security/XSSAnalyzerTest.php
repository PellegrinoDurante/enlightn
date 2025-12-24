<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\XSSAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;

class XSSAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithMiddleware;

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(XSSAnalyzer::class, $app);
    }

    #[Test]
    public function skips_for_stateless_apps()
    {
        $this->runEnlightn();

        $this->assertSkipped(XSSAnalyzer::class);
    }

    #[Test]
    public function skips_for_local()
    {
        $this->app->config->set('app.env', 'local');

        $this->registerStatefulGlobalMiddleware();

        $this->runEnlightn();

        $this->assertSkipped(XSSAnalyzer::class);
    }

    #[Test]
    public function detects_missing_csp_header()
    {
        $this->registerStatefulGlobalMiddleware();

        $this->app->make(XSSAnalyzer::class)->setClient(new Client(
            ['handler' => new MockHandler([
                new Response(200, []),
            ])]
        ));

        Route::get('/login', function () {
            //
        })->name('login');

        $this->runEnlightn();

        $this->assertFailed(XSSAnalyzer::class);
    }

    #[Test]
    public function detects_unsafe_csp_header()
    {
        $this->registerStatefulGlobalMiddleware();

        $this->app->make(XSSAnalyzer::class)->setClient(new Client(
            ['handler' => new MockHandler([
                new Response(200, ['Content-Security-Policy' => "default-src 'self' 'unsafe-inline'"]),
            ])]
        ));

        Route::get('/login', function () {
            //
        })->name('login');

        $this->runEnlightn();

        $this->assertFailed(XSSAnalyzer::class);
    }

    #[Test]
    public function passes_for_default_csp_header()
    {
        $this->registerStatefulGlobalMiddleware();

        $this->app->make(XSSAnalyzer::class)->setClient(new Client(
            ['handler' => new MockHandler([
                new Response(200, ['Content-Security-Policy' => "default-src 'self'"]),
            ])]
        ));

        Route::get('/login', function () {
            //
        })->name('login');

        $this->runEnlightn();

        $this->assertPassed(XSSAnalyzer::class);
    }

    #[Test]
    public function passes_for_script_csp_header()
    {
        $this->registerStatefulGlobalMiddleware();

        $this->app->make(XSSAnalyzer::class)->setClient(new Client(
            ['handler' => new MockHandler([
                new Response(200, ['Content-Security-Policy' => "script-src 'self'"]),
            ])]
        ));

        Route::get('/login', function () {
            //
        })->name('login');

        $this->runEnlightn();

        $this->assertPassed(XSSAnalyzer::class);
    }
}
