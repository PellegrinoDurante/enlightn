<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\DeadCodeAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DeadCodeStub;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use PHPUnit\Framework\Attributes\Test;

class DeadCodeAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(DeadCodeAnalyzer::class, $app);
    }

    #[Test]
    public function detects_dead_code()
    {
        $this->setBasePathFrom(DeadCodeStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(DeadCodeAnalyzer::class, $this->getClassStubPath(DeadCodeStub::class), 10);
        $this->assertFailedAt(DeadCodeAnalyzer::class, $this->getClassStubPath(DeadCodeStub::class), 16);
        $this->assertFailedAt(DeadCodeAnalyzer::class, $this->getClassStubPath(DeadCodeStub::class), 24);
        $this->assertFailedAt(DeadCodeAnalyzer::class, $this->getClassStubPath(DeadCodeStub::class), 35);
        $this->assertFailedAt(DeadCodeAnalyzer::class, $this->getClassStubPath(DeadCodeStub::class), 42);
        $this->assertFailedAt(DeadCodeAnalyzer::class, $this->getClassStubPath(DeadCodeStub::class), 47);
        $this->assertHasErrors(DeadCodeAnalyzer::class, 6);
    }

    #[Test]
    public function passes_with_no_dead_code()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(DeadCodeAnalyzer::class);
    }
}
