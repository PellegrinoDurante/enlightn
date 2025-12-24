<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\SyntaxErrorAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use PHPUnit\Framework\Attributes\Test;

class SyntaxErrorAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(SyntaxErrorAnalyzer::class, $app);
    }

    #[Test]
    public function detects_syntax_errors()
    {
        $this->app->config->set('enlightn.base_path', $this->getBaseStubPath());

        $this->runEnlightn();

        $errorPath = $this->getBaseStubPath().DIRECTORY_SEPARATOR.'SyntaxErrorStub.php';

        $this->assertFailedAt(SyntaxErrorAnalyzer::class, $errorPath, 5);
        $this->assertFailedAt(SyntaxErrorAnalyzer::class, $errorPath, 7);
        $this->assertHasErrors(SyntaxErrorAnalyzer::class, 2);
    }

    #[Test]
    public function passes_with_no_errors()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(SyntaxErrorAnalyzer::class);
    }
}
