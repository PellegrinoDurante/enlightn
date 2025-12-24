<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\AppKeyAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class AppKeyAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(AppKeyAnalyzer::class, $app);
    }

    #[Test]
    public function detects_no_app_key()
    {
        $this->app->config->set('app.key', null);

        $this->runEnlightn();

        $this->assertFailedAt(AppKeyAnalyzer::class, $this->getConfigStubPath('app'), 122);
    }

    #[Test]
    public function detects_incompatible_key_and_cipher()
    {
        $this->app->config->set('app.key', 'blahblah');

        $this->runEnlightn();

        $this->assertFailedAt(AppKeyAnalyzer::class, $this->getConfigStubPath('app'), 122);
    }

    #[Test]
    public function passes_with_proper_app_key_and_cipher()
    {
        $this->app->config->set('app.key', Str::random(32));
        $this->app->config->set('app.cipher', 'AES-256-CBC');

        $this->runEnlightn();

        $this->assertPassed(AppKeyAnalyzer::class);
    }
}
