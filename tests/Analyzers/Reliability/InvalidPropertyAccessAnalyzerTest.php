<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidPropertyAccessAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\InvalidAccessPropertiesStub;
use PHPUnit\Framework\Attributes\Test;

class InvalidPropertyAccessAnalyzerTest extends AnalyzerTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->setupEnvironmentFor(InvalidPropertyAccessAnalyzer::class, $app);
    }

    #[Test]
    public function detects_invalid_property_access()
    {
        $this->setBasePathFrom(InvalidAccessPropertiesStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(InvalidPropertyAccessAnalyzer::class, $this->getClassStubPath(InvalidAccessPropertiesStub::class), 16);
        $this->assertFailedAt(InvalidPropertyAccessAnalyzer::class, $this->getClassStubPath(InvalidAccessPropertiesStub::class), 17);
        $this->assertHasErrors(InvalidPropertyAccessAnalyzer::class, 2);
    }

    #[Test]
    public function passes_with_no_invalid_access()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidPropertyAccessAnalyzer::class);
    }
}
