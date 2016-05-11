<?php

namespace Phpro\DatatablesBundle\Tests\Request;

use Phpro\DatatablesBundle\Request\Request as DatatableRequest;
use Phpro\DatatablesBundle\Request\RequestInterface;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;
use Symfony\Component\HttpFoundation\Request;

class RequestTest extends DatatablesTestCase
{
    public function testItIsInitializable()
    {
        $request = new DatatableRequest($http = $this->mockRequest());

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertInstanceOf(Request::class, $request->getHttpRequest());
        $this->assertEquals($http, $request->getHttpRequest());
    }

    public function testGetPageSizeReturnsDefaultWhenNoneFound()
    {
        $request = new DatatableRequest(new Request());
        $this->assertEquals(10, $request->getPageSize());
    }

    public function testCanGetPageSizeFromRequest()
    {
        $http = $this->mockRequest($pageSize = 15);
        $request =  new DatatableRequest($http);

        $this->assertEquals($pageSize, $request->getPageSize());
    }

    public function testGetPageReturnsDefaultWhenNoneFound()
    {
        $request = new DatatableRequest(new Request());
        $this->assertEquals(1, $request->getPage());
    }

    public function testCanGetPageFromRequest()
    {
        $request = new DatatableRequest($this->mockRequest(5, 21));
        $this->assertEquals(5, $request->getPage());
    }

    public function testGetOffsetReturnsDefaultWhenNoneFound()
    {
        $request = new DatatableRequest(new Request());
        $this->assertEquals(0, $request->getOffset());
    }

    public function testCanGetOffsetFromRequest()
    {
        $request = new DatatableRequest($this->mockRequest(5, 21));
        $this->assertEquals(21, $request->getOffset());
    }

    public function testGetSortReturnsDefaultWhenNoneFound()
    {
        $request = new DatatableRequest(new Request());
        $this->assertEquals('id', $request->getSort());
    }

    public function testCanGetSortFromRequest()
    {
        $request = new DatatableRequest($this->mockRequest(5, 21));
        $this->assertEquals('name', $request->getSort());
    }

    public function testGetOrderReturnsDefaultWhenNoneFound()
    {
        $request = new DatatableRequest(new Request());
        $this->assertEquals('desc', $request->getOrder());
    }

    public function testCanGetOrderFromRequest()
    {
        $request = new DatatableRequest($this->mockRequest(5, 21));
        $this->assertEquals('asc', $request->getOrder());
    }

    public function testGetSearchReturnsDefaultWhenNoneFound()
    {
        $request = new DatatableRequest(new Request());
        $this->assertEquals('', $request->getSearch());
    }

    public function testCanGetSearchFromRequest()
    {
        $request = new DatatableRequest($this->mockRequest(5, 21, 5, $search = 'searchValue'));
        $this->assertEquals($search, $request->getSearch());
    }

    public function testGetDrawReturnsDefaultWhenNoneFound()
    {
        $request = new DatatableRequest(new Request());
        $this->assertEquals(0, $request->getDraw());
    }

    public function testCanGetDrawFromRequest()
    {
        $request = new DatatableRequest($this->mockRequest(5, 21, $draw = 5));
        $this->assertEquals(5, $request->getDraw());
    }

    /**
     * Mocks the HttpRequest object
     *
     * @param int $pageSize
     * @param int $start
     * @param int $draw
     * @param string $search
     * @return \PHPUnit_Framework_MockObject_MockObject|Request
     */
    private function mockRequest($pageSize = 10, $start = 0, $draw = 0, $search = 'some-search')
    {
        $request = new Request([
            'length'  => $pageSize,
            'search'  => ['value' => $search],
            'draw'    => $draw,
            'start'   => $start,
            'order'   => [0 => ['column' => 1, 'dir' => 'asc']],
            'columns' => [1 => ['orderable' => 'true', 'name' => 'name']],
        ]);

        return $request;
    }
}
