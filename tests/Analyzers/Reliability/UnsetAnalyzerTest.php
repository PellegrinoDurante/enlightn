<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\UnsetAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\UnsetStub;
use PHPUnit\Framework\Attributes\Test;

class UnsetAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(UnsetAnalyzer::class, $app);
    }

    #[Test]
    public function detects_invalid_unset_statements()
    {
        $this->setBasePathFrom(UnsetStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 9);
        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 10);
        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 13);
        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 16);
        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 19);
        $this->assertHasErrors(UnsetAnalyzer::class, 5);
    }

    #[Test]
    public function passes_with_no_unset_statements()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(UnsetAnalyzer::class);
    }
}
