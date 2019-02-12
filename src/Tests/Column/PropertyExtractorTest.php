<?php
namespace DigipolisGent\DatatablesBundle\Tests\Column;

use DigipolisGent\DatatablesBundle\Column\PropertyExtractor;
use DigipolisGent\DatatablesBundle\Tests\DatatablesTestCase;

class PropertyExtractorTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $extractor = new PropertyExtractor('some_var');
        $this->assertInstanceOf(PropertyExtractor::class, $extractor);
    }

    public function testIsCallable()
    {
        $extractor = new PropertyExtractor('test');
        $this->assertTrue(is_callable($extractor));
    }

    public function testExtractsProperty()
    {
        $subject = new \stdClass();
        $subject->test = 'some_value';
        $extractor = new PropertyExtractor('test');

        $this->assertEquals($subject->test, $extractor($subject));
    }
}
