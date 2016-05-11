<?php

namespace Phpro\DatatablesBundle\Tests\Datatable;

use Phpro\DatatablesBundle\Column\Column;
use Phpro\DatatablesBundle\Column\ColumnInterface;
use Phpro\DatatablesBundle\DataExtractor\DataExtractorInterface;
use Phpro\DatatablesBundle\DataExtractor\Extraction;
use Phpro\DatatablesBundle\Datatable\Datatable;
use Phpro\DatatablesBundle\Datatable\DatatableInterface;
use Phpro\DatatablesBundle\Request\RequestInterface;
use Phpro\DatatablesBundle\Response\Response;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class DatatableTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $extractor = $this->getMock(DataExtractorInterface::class);
        $table = new Datatable('test', $extractor);
        $this->assertInstanceOf(DatatableInterface::class, $table);
        $this->assertEquals('test', $table->getAlias());
    }

    public function testCanAddColumns()
    {
        $extractor = $this->getMock(DataExtractorInterface::class);
        $table = new Datatable('test', $extractor);

        $this->assertTrue(is_array($table->getColumns()));
        $this->assertCount(0, $table->getColumns());

        $table->addColumn(new Column('name'));

        $this->assertCount(1, $table->getColumns());
    }

    public function testCanCreateColumns()
    {
        $extractor = $this->getMock(DataExtractorInterface::class);
        $table = new Datatable('test', $extractor);

        $this->assertTrue(is_array($table->getColumns()));
        $this->assertCount(0, $table->getColumns());

        $table->createColumn('test');
        $table->createColumn('name');

        $this->assertInstancesOf(ColumnInterface::class, $table->getColumns());
    }

    public function testBuildResponse()
    {
        $extractor = $this->getMock(DataExtractorInterface::class);
        $column    = $this->getMock(ColumnInterface::class);
        $request   = $this->getMock(RequestInterface::class);

        $extractor
            ->method('extract')
            ->willReturn(new Extraction([new \stdClass()], 1));

        $column
            ->method('extractValue')
            ->willReturn('some-value');

        $request
            ->method('getDraw')
            ->willReturn(0);

        $table = new Datatable('test', $extractor);
        $table->addColumn($column);


        $response = $table->buildResponse($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertContains('some-value', $response->getContent());
    }
}
