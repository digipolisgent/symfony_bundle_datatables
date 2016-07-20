<?php

namespace Avdb\DatatablesBundle\Tests\Controller;

use Avdb\DatatablesBundle\Controller\DataController;
use Avdb\DatatablesBundle\Datatable\DatatableInterface;
use Avdb\DatatablesBundle\Manager\DatatableManagerInterface;
use Avdb\DatatablesBundle\Tests\DatatablesTestCase;
use Avdb\DatatablesBundle\Response\Response;
use Symfony\Component\HttpFoundation\Request;

class DataControllerTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        /** @var DatatableManagerInterface $manager */
        $manager = $this->getMock(DatatableManagerInterface::class);

        $controller = new DataController($manager);
        $this->assertInstanceOf(DataController::class, $controller);
    }

    public function testReturnsResponseWhenTableIsFound()
    {
        /** @var Request $request */
        $request = $this->getMock(Request::class);
        $table = $this->getMock(DatatableInterface::class);
        $table
            ->method('buildResponse')
            ->willReturn(new Response([], 10, 1));

        /** @var DatatableInterface $table */

        $manager = $this->getMock(DatatableManagerInterface::class);
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
