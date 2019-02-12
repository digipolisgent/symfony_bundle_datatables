<?php

namespace DigipolisGent\DatatablesBundle\Tests\Manager;

use DigipolisGent\DatatablesBundle\Datatable\DatatableInterface;
use DigipolisGent\DatatablesBundle\Exception\DatatableNotFoundException;
use DigipolisGent\DatatablesBundle\Exception\RuntimeException;
use DigipolisGent\DatatablesBundle\Manager\DatatableManager;
use DigipolisGent\DatatablesBundle\Tests\DatatablesTestCase;

class DatatableManagerTest extends DatatablesTestCase
{
    public function testCanAddDatatable()
    {
        $manager = new DatatableManager();
        $manager->add($this->mockTable('mock'));

        $this->assertInstanceOf(DatatableInterface::class, $manager->get('mock'));
    }

    public function testHasMethod()
    {
        $manager = new DatatableManager();
        $this->assertFalse($manager->has('some-alias'));
        $manager->add($this->mockTable('some-alias'));
        $this->assertTrue($manager->has('some-alias'));
    }

    public function testShouldThrowExceptionWhenGetIsCalledAndTableNotPresent()
    {
        $manager = new DatatableManager();
        $this->expectException(DatatableNotFoundException::class);
        $manager->get('some-alias');
    }

    public function testReturnsTableWhenGetIsCalled()
    {
        $manager = new DatatableManager();
        $manager->add($this->mockTable('mock'));
        $this->assertInstanceOf(DatatableInterface::class, $manager->get('mock'));
    }

    public function testThrowsExceptionWhenTableIsAddedTwice()
    {
        $manager = new DatatableManager();
        $table = $this->mockTable('duplicate');

        $manager->add($table);
        $this->expectException(RuntimeException::class);
        $manager->add($table);
    }
}
