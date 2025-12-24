<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\MysqlSingleServerAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use PHPUnit\Framework\Attributes\Test;

class MysqlSingleServerAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->config->set('app.env', 'production');
        $app->config->set('database.default', 'mysql');

        $this->setupEnvironmentFor(MysqlSingleServerAnalyzer::class, $app);
    }

    #[Test]
    public function detects_single_server_setup_without_sockets()
    {
        $this->runEnlightn();

        $this->assertFailedAt(MysqlSingleServerAnalyzer::class, $this->getConfigStubPath('database'), 54);
    }

    #[Test]
    public function passes_single_server_setup_with_unix_sockets()
    {
        $this->app->config->set('database.connections.mysql.unix_socket', '/path/to/some/mysql.sock');

        $this->runEnlightn();

        $this->assertPassed(MysqlSingleServerAnalyzer::class);
    }
}
