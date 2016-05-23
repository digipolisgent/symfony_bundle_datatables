<?php
namespace Avdb\DatatablesBundle\DataExtractor;

use Avdb\DatatablesBundle\Request\RequestInterface;

/**
 * Interface DataExtractorInterface
 *
 * @package Avdb\DatatablesBundle\DataExtractor
 */
interface DataExtractorInterface
{
    /**
     * @param RequestInterface $request
     * @return ExtractionInterface
     */
    public function extract(RequestInterface $request);
}
