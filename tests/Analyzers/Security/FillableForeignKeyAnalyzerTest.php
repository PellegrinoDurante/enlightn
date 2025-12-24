<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\FillableForeignKeyAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\FillableForeignKeyStub;
use PHPUnit\Framework\Attributes\Test;

class FillableForeignKeyAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(FillableForeignKeyAnalyzer::class, $app);
    }

    #[Test]
    public function detects_validation_sql_injection()
    {
        $this->setBasePathFrom(FillableForeignKeyStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(FillableForeignKeyAnalyzer::class, $this->getClassStubPath(FillableForeignKeyStub::class), 10);
        $this->assertHasErrors(FillableForeignKeyAnalyzer::class, 1);
    }

    #[Test]
    public function passes_with_no_injection_call()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(FillableForeignKeyAnalyzer::class);
    }
}
