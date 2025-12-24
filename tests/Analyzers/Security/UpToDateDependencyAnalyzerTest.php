<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\UpToDateDependencyAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithComposer;
use PHPUnit\Framework\Attributes\Test;

class UpToDateDependencyAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithComposer;

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $this->replaceComposer($app);

        $this->setupEnvironmentFor(UpToDateDependencyAnalyzer::class, $app);
    }

    #[Test]
    public function passes_with_up_to_date_dependencies()
    {
        $this->runEnlightn();

        $this->assertPassed(UpToDateDependencyAnalyzer::class);
    }
}
