<?php

namespace DigipolisGent\DatatablesBundle\Tests\Controller;

use DigipolisGent\DatatablesBundle\Controller\DataController;
use DigipolisGent\DatatablesBundle\Datatable\DatatableInterface;
use DigipolisGent\DatatablesBundle\Manager\DatatableManagerInterface;
use DigipolisGent\DatatablesBundle\Tests\DatatablesTestCase;
use DigipolisGent\DatatablesBundle\Response\Response;
use Symfony\Component\HttpFoundation\Request;

class DataControllerTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        /** @var DatatableManagerInterface $manager */
        $manager = $this->getMockObject(DatatableManagerInterface::class);

        $controller = new DataController($manager);
        $this->assertInstanceOf(DataController::class, $controller);
    }

    public function testReturnsResponseWhenTableIsFound()
    {
        /** @var Request $request */
        $request = $this->getMockObject(Request::class);
        $table = $this->getMockObject(DatatableInterface::class);
        $table
            ->method('buildResponse')
            ->willReturn(new Response([], 10, 1));

        /** @var DatatableInterface $table */

        $manager = $this->getMockObject(DatatableManagerInterface::class);
        $manager
            ->method('has')
            ->willReturn(true);

        $manager
            ->method('get')
            ->willReturn($table);

        /** @var DatatableManagerInterface $manager */

        $controller = new DataController($manager);

        $this->assertInstanceOf(Response::class, $controller->dataAction($request, 'some_table'));
    }
}
