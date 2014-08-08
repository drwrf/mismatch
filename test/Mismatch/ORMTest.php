<?php

namespace Mismatch;

class ORMTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->metadata = Metadata::get('Mismatch\Model\ORMMock');
    }

    public function test_usingORM_setsTable()
    {
        $this->assertEquals('mismatch_model_orm_mock', $this->metadata['table']);
    }

    public function test_usingORM_setsPk()
    {
        $this->assertEquals('id', $this->metadata['pk']);
    }
}

namespace Mismatch\Model;

use Mismatch;

class OrmMock
{
    use Mismatch\ORM;
}
