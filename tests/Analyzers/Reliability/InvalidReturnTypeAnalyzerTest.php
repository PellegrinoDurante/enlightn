<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidReturnTypeAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\InvalidReturnTypeStub;
use PHPUnit\Framework\Attributes\Test;

class InvalidReturnTypeAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(InvalidReturnTypeAnalyzer::class, $app);
    }

    #[Test]
    public function detects_invalid_offset()
    {
        $this->setBasePathFrom(InvalidReturnTypeStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 14);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 28);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 32);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 44);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 58);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 62);
        $this->assertHasErrors(InvalidReturnTypeAnalyzer::class, 6);
    }

    #[Test]
    public function passes_with_no_offset()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidReturnTypeAnalyzer::class);
    }
}
