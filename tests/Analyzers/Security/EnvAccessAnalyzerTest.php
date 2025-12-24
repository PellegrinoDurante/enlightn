<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\EnvAccessAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;

class EnvAccessAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(EnvAccessAnalyzer::class, $app);
    }

    #[Test]
    public function detects_publicly_accessible_env_file()
    {
        $this->app->make(EnvAccessAnalyzer::class)->setClient(new Client(
            ['handler' => new MockHandler([
                new Response(200, [], file_get_contents($this->getBaseStubPath().DIRECTORY_SEPARATOR.'.env.local')),
            ])]
        ));

        $this->runEnlightn();

        $this->assertFailed(EnvAccessAnalyzer::class);
    }

    #[Test]
    public function passes_with_safe_setup()
    {
        $this->app->make(EnvAccessAnalyzer::class)->setClient(new Client(
            ['handler' => new MockHandler([
                new Response(404),
            ])]
        ));

        $this->runEnlightn();

        $this->assertPassed(EnvAccessAnalyzer::class);
    }
}
