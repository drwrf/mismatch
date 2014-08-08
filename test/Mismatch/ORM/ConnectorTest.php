<?php

namespace Mismatch\ORM;

class ConnectorTest extends \PHPUnit_Framework_TestCase
{
    public function test_connect_pools()
    {
        $conn1 = Connector::connect([
            'driver' => 'pdo_sqlite',
            'memory' => 'true',
        ]);

        $conn2 = Connector::connect([
            'driver' => 'pdo_sqlite',
            'memory' => 'true',
        ]);

        $this->assertSame($conn1, $conn2);
    }
}
