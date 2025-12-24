<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\UndefinedVariableAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\UndefinedVariableStub;
use PHPUnit\Framework\Attributes\Test;

class UndefinedVariableAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(UndefinedVariableAnalyzer::class, $app);
    }

    #[Test]
    public function detects_undefined_variables()
    {
        $this->setBasePathFrom(UndefinedVariableStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(UndefinedVariableAnalyzer::class, $this->getClassStubPath(UndefinedVariableStub::class), 9);
        $this->assertFailedAt(UndefinedVariableAnalyzer::class, $this->getClassStubPath(UndefinedVariableStub::class), 22);
        $this->assertHasErrors(UndefinedVariableAnalyzer::class, 2);
    }

    #[Test]
    public function passes_with_no_undefined_variables()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(UndefinedVariableAnalyzer::class);
    }
}
