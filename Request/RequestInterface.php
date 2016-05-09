<?php
declare(strict_types = 1);
namespace phpro\DatatablesBundle\Request;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Interface RequestInterface
 *
 * @package phpro\DatatablesBundle\Request
 */
interface RequestInterface
{
    /**
     * @return HttpRequest
     */
    public function getHttpRequest() : HttpRequest;

    /**
     * Get Page size from request
     *
     * @return integer
     */
    public function getPageSize() : int;

    /**
     * Get page number from request
     *
     * @return int
     */
    public function getPage() : int;

    /**
     * Returns the offset, if this is preferred above getPage
     * 
     * @return int
     */
    public function getOffset() : int;

    /**
     * Extracts the sort parameter from the request object
     *
     * @return string
     */
    public function getSort() : string;

    /**
     * Extracts the order parameter from the request object
     *
     * @return string
     */
    public function getOrder() : string;

    /**
     * Extracts the search parameter from the request object
     *
     * @return string
     */
    public function getSearch() : string;

    /**
     * @return int
     */
    public function getDraw() : int;
}
