<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\DeprecatedCodeAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DeprecatedCodeStub;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use PHPUnit\Framework\Attributes\Test;

class DeprecatedCodeAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(DeprecatedCodeAnalyzer::class, $app);
    }

    #[Test]
    public function detects_deprecated_code()
    {
        $this->setBasePathFrom(DeprecatedCodeStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(DeprecatedCodeAnalyzer::class, $this->getClassStubPath(DeprecatedCodeStub::class), 9);
        $this->assertFailedAt(DeprecatedCodeAnalyzer::class, $this->getClassStubPath(DeprecatedCodeStub::class), 10);
        $this->assertFailedAt(DeprecatedCodeAnalyzer::class, $this->getClassStubPath(DeprecatedCodeStub::class), 13);
        $this->assertFailedAt(DeprecatedCodeAnalyzer::class, $this->getClassStubPath(DeprecatedCodeStub::class), 14);
        $this->assertFailedAt(DeprecatedCodeAnalyzer::class, $this->getClassStubPath(DeprecatedCodeStub::class), 16);
        $this->assertFailedAt(DeprecatedCodeAnalyzer::class, $this->getClassStubPath(DeprecatedCodeStub::class), 18);
        $this->assertFailedAt(DeprecatedCodeAnalyzer::class, $this->getClassStubPath(DeprecatedCodeStub::class), 20);
        $this->assertFailedAt(DeprecatedCodeAnalyzer::class, $this->getClassStubPath(DeprecatedCodeStub::class), 77);
        $this->assertFailedAt(DeprecatedCodeAnalyzer::class, $this->getClassStubPath(DeprecatedCodeStub::class), 90);
        $this->assertHasErrors(DeprecatedCodeAnalyzer::class, 9);
    }

    #[Test]
    public function passes_with_no_deprecated_code()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(DeprecatedCodeAnalyzer::class);
    }
}
