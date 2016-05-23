<?php
namespace Phpro\DatatablesBundle\DataExtractor;

use Phpro\DatatablesBundle\Request\RequestInterface;

/**
 * Interface DataExtractorInterface
 *
 * @package Phpro\DatatablesBundle\DataExtractor
 */
interface DataExtractorInterface
{
    /**
     * @param RequestInterface $request
     * @return ExtractionInterface
     */
    public function extract(RequestInterface $request);
}
