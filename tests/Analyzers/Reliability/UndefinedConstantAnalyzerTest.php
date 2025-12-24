<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\UndefinedConstantAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\UndefinedConstantStub;
use PHPUnit\Framework\Attributes\Test;

class UndefinedConstantAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(UndefinedConstantAnalyzer::class, $app);
    }

    #[Test]
    public function detects_missing_return_statements()
    {
        $this->setBasePathFrom(UndefinedConstantStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(UndefinedConstantAnalyzer::class, $this->getClassStubPath(UndefinedConstantStub::class), 9);
        $this->assertHasErrors(UndefinedConstantAnalyzer::class, 1);
    }

    #[Test]
    public function passes_with_no_return_statements()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(UndefinedConstantAnalyzer::class);
    }
}
