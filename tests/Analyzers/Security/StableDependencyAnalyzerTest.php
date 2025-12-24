<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\StableDependencyAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithComposer;
use PHPUnit\Framework\Attributes\Test;

class StableDependencyAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithComposer;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->replaceComposer($app);

        $this->setupEnvironmentFor(StableDependencyAnalyzer::class, $app);
    }

    #[Test]
    public function confirms_enlightn_has_stable_dependencies()
    {
        $this->runEnlightn();

        $this->assertPassed(StableDependencyAnalyzer::class);
    }
}
