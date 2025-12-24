<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\ForeachIterableAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\ForeachIterableStub;
use PHPUnit\Framework\Attributes\Test;

class ForeachIterableAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(ForeachIterableAnalyzer::class, $app);
    }

    #[Test]
    public function detects_non_iterable_foreach()
    {
        $this->setBasePathFrom(ForeachIterableStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(ForeachIterableAnalyzer::class, $this->getClassStubPath(ForeachIterableStub::class), 10);
        $this->assertFailedAt(ForeachIterableAnalyzer::class, $this->getClassStubPath(ForeachIterableStub::class), 19);
        $this->assertHasErrors(ForeachIterableAnalyzer::class, 2);
    }

    #[Test]
    public function passes_with_no_foreach()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(ForeachIterableAnalyzer::class);
    }
}
