<?php
namespace Avdb\DatatablesBundle\Request;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Interface RequestInterface
 *
 * @package Avdb\DatatablesBundle\Request
 */
interface RequestInterface
{
    /**
     * @return HttpRequest
     */
    public function getHttpRequest();

    /**
     * Get Page size from request
     *
     * @return integer
     */
    public function getPageSize();

    /**
     * Get page number from request
     *
     * @return int
     */
    public function getPage();

    /**
     * Returns the offset, if this is preferred above getPage
     * 
     * @return int
     */
    public function getOffset();

    /**
     * Extracts the sort parameter from the request object
     *
     * @return string
     */
    public function getSort();

    /**
     * Extracts the order parameter from the request object
     *
     * @return string
     */
    public function getOrder();

    /**
     * Extracts the search parameter from the request object
     *
     * @return string
     */
    public function getSearch();

    /**
     * @return int
     */
    public function getDraw();
}
