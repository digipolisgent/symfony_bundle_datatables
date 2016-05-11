<?php

namespace Phpro\DatatablesBundle\Tests\Response;

use Phpro\DatatablesBundle\Response\Response;
use Phpro\DatatablesBundle\Tests\DatatablesTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseTest extends DatatablesTestCase
{
    public function testCanInitialize()
    {
        $response = new Response([],0, 0);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testResponseArguments()
    {
        $response = new Response(['some-object' => 'some-value'], 1234, 999);

        $this->assertContains('data', $response->getContent());
        $this->assertContains('recordsFiltered', $response->getContent());
        $this->assertContains('recordsTotal', $response->getContent());
        $this->assertContains('draw', $response->getContent());
        $this->assertContains('some-object', $response->getContent());
        $this->assertContains('some-value', $response->getContent());
        $this->assertContains('1234', $response->getContent());
        $this->assertContains('999', $response->getContent());
    }
}
