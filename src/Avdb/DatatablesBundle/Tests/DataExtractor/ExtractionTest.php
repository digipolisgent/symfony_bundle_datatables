<?php

namespace Avdb\DatatablesBundle\Tests\DataExtractor;

use Avdb\DatatablesBundle\DataExtractor\Extraction;
use Avdb\DatatablesBundle\DataExtractor\ExtractionInterface;
use Avdb\DatatablesBundle\Tests\DatatablesTestCase;

class ExtractionTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $result = new Extraction([], 0);
        $this->assertInstanceOf(ExtractionInterface::class, $result);
        $this->assertInstanceOf(Extraction::class, $result);
    }

    public function testCanGetDataAndTotal()
    {
        $result = new Extraction($data = ['some' => 'data'], $total = 1);

        $this->assertEquals($data, $result->getData());
        $this->assertEquals($total, $result->getTotalRecords());
    }
}
