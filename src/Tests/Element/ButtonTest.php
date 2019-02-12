<?php

namespace DigipolisGent\DatatablesBundle\Tests\Element;

use DigipolisGent\DatatablesBundle\Element\Button;
use DigipolisGent\DatatablesBundle\Tests\DatatablesTestCase;

class ButtonTest extends DatatablesTestCase
{
    public function testHasDefaultOptions()
    {
        $button = Button::generate();
        $this->assertEquals('<a href="#" class="btn btn-danger btn-flat " >Edit</a>', $button);
    }
    
    public function testGenerateButtonWithOptions()
    {
        $button = Button::generate([
            'link' => 'http://google.be',
            'type' => 'warning',
            'class' => 'some-class',
            'text' => 'mocked button'
        ]);

        $this->assertContains('href="http://google.be"', $button);
        $this->assertContains('>mocked button</a>', $button);
        $this->assertContains('class="btn btn-warning btn-flat some-class"', $button);
    }
}
