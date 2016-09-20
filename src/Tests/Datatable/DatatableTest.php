<?php

namespace Avdb\DatatablesBundle\Tests\Datatable;

use Avdb\DatatablesBundle\Column\Column;
use Avdb\DatatablesBundle\Column\ColumnInterface;
use Avdb\DatatablesBundle\DataExtractor\DataExtractorInterface;
use Avdb\DatatablesBundle\DataExtractor\Extraction;
use Avdb\DatatablesBundle\Datatable\Datatable;
use Avdb\DatatablesBundle\Datatable\DatatableInterface;
use Avdb\DatatablesBundle\Exception\RuntimeException;
use Avdb\DatatablesBundle\Request\RequestInterface;
use Avdb\DatatablesBundle\Response\Response;
use Avdb\DatatablesBundle\Tests\DatatablesTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class DatatableTest extends DatatablesTestCase
{
    public function testIsInitializable()
    {
        $extractor = $this->getMockObject(DataExtractorInterface::class);
        $table = new Datatable('test', $extractor);
        $this->assertInstanceOf(DatatableInterface::class, $table);
        $this->assertEquals('test', $table->getAlias());
    }

    public function testCanAddColumns()
    {
        $extractor = $this->getMockObject(DataExtractorInterface::class);
        $table = new Datatable('test', $extractor);

        $this->assertTrue(is_array($table->getColumns()));
        $this->assertCount(0, $table->getColumns());

        $table->addColumn(new Column('name'));

        $this->assertCount(1, $table->getColumns());
    }

    public function testCanCreateColumns()
    {
        $extractor = $this->getMockObject(DataExtractorInterface::class);
        $table = new Datatable('test', $extractor);

        $this->assertTrue(is_array($table->getColumns()));
        $this->assertCount(0, $table->getColumns());

        $table->createColumn('test');
        $table->createColumn('name');

        $this->assertInstancesOf(ColumnInterface::class, $table->getColumns());
    }
    
    public function testShouldThrowExceptionWhenNoExtractionIsReturned()
    {
        $request   = $this->getMockObject(RequestInterface::class);
        $extractor = $this->getMockObject(DataExtractorInterface::class);
        $extractor
            ->method('extract')
            ->with($request)
            ->willReturn(['something-wong']);
        
        $this->expectException(RuntimeException::class);
        $table = new Datatable('test', $extractor);
        $table->buildResponse($request);
    }

    public function testBuildResponse()
    {
        $extractor = $this->getMockObject(DataExtractorInterface::class);
        $column    = $this->getMockObject(ColumnInterface::class);
        $request   = $this->getMockObject(RequestInterface::class);

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
