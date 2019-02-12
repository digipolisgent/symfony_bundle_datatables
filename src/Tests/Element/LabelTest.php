<?php
namespace DigipolisGent\DatatablesBundle\Tests\Element;

use DigipolisGent\DatatablesBundle\Element\Label;
use DigipolisGent\DatatablesBundle\Tests\DatatablesTestCase;

class LabelTest extends DatatablesTestCase
{
    public function testHasDefaultValues()
    {
        $label = Label::generate();
        $this->assertEquals('<span class="label label-success ">yes</span>', $label);
    }

    public function testCanGenerateLabelWithOptions()
    {
        $label = Label::generate([
            'type' => 'danger',
            'class' => 'some-class',
            'text' => 'some-text'
        ]);

        $this->assertContains('class="label label-danger some-class"', $label);
        $this->assertContains('>some-text</span>', $label);
    }
}
