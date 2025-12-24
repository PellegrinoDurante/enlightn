<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidOffsetAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\InvalidOffsetStub;
use PHPUnit\Framework\Attributes\Test;

class InvalidOffsetAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(InvalidOffsetAnalyzer::class, $app);
    }

    #[Test]
    public function detects_invalid_offset()
    {
        $this->setBasePathFrom(InvalidOffsetStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(InvalidOffsetAnalyzer::class, $this->getClassStubPath(InvalidOffsetStub::class), 10);
        $this->assertFailedAt(InvalidOffsetAnalyzer::class, $this->getClassStubPath(InvalidOffsetStub::class), 13);
        $this->assertFailedAt(InvalidOffsetAnalyzer::class, $this->getClassStubPath(InvalidOffsetStub::class), 17);
        $this->assertFailedAt(InvalidOffsetAnalyzer::class, $this->getClassStubPath(InvalidOffsetStub::class), 25);
        $this->assertHasErrors(InvalidOffsetAnalyzer::class, 5);
    }

    #[Test]
    public function passes_with_no_offset()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidOffsetAnalyzer::class);
    }
}
