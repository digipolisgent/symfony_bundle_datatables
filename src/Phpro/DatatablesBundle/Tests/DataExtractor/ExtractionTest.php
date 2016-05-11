<?php

namespace Phpro\DatatablesBundle\Tests\DataExtractor;

use Phpro\DatatablesBundle\DataExtractor\Extraction;
use Phpro\DatatablesBundle\DataExtractor\ExtractionInterface;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;

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
