<?php
namespace DigipolisGent\DatatablesBundle\DataExtractor;

use DigipolisGent\DatatablesBundle\Request\RequestInterface;

/**
 * Interface DataExtractorInterface
 *
 * @package DigipolisGent\DatatablesBundle\DataExtractor
 */
interface DataExtractorInterface
{
    /**
     * @param RequestInterface $request
     * @return ExtractionInterface
     */
    public function extract(RequestInterface $request);
}
