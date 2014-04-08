<?php

namespace Mismatch;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = Metadata::get('Mismatch\Model\Mock');
    }
}

namespace Mismatch\Model;

use Mismatch;

class User
{
    use Mismatch\Model;
}
