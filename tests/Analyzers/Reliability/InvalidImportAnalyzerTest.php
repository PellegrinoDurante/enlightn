<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidImportAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\InvalidImportStub;
use PHPUnit\Framework\Attributes\Test;

class InvalidImportAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(InvalidImportAnalyzer::class, $app);
    }

    #[Test]
    public function detects_missing_return_statements()
    {
        $this->setBasePathFrom(InvalidImportStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(InvalidImportAnalyzer::class, $this->getClassStubPath(InvalidImportStub::class), 5);
        $this->assertHasErrors(InvalidImportAnalyzer::class, 1);
    }

    #[Test]
    public function passes_with_no_return_statements()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidImportAnalyzer::class);
    }
}
