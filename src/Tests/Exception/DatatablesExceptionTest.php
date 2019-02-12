<?php

namespace DigipolisGent\DatatablesBundle\Tests\Exception;

use DigipolisGent\DatatablesBundle\Exception\DatatablesException;
use DigipolisGent\DatatablesBundle\Tests\DatatablesTestCase;

class DatatablesExceptionTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $exception = new DatatablesException;
        $this->assertInstanceOf(DatatablesException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
