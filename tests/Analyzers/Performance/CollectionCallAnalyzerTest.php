<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\CollectionCallAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\CollectionStub;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use PHPUnit\Framework\Attributes\Test;

class CollectionCallAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(CollectionCallAnalyzer::class, $app);
    }

    #[Test]
    public function detects_suboptimal_collection_call()
    {
        $this->setBasePathFrom(CollectionStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(CollectionCallAnalyzer::class, $this->getClassStubPath(CollectionStub::class), 11);
        $this->assertFailedAt(CollectionCallAnalyzer::class, $this->getClassStubPath(CollectionStub::class), 16);
        $this->assertHasErrors(CollectionCallAnalyzer::class, 2);
    }

    #[Test]
    public function passes_with_no_collection_call()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(CollectionCallAnalyzer::class);
    }
}
