<?php
declare(strict_types = 1);
namespace phpro\DatatablesBundle\DataExtractor;

use phpro\DatatablesBundle\Request\RequestInterface;

/**
 * Interface DataExtractorInterface
 *
 * @package phpro\DatatablesBundle\DataExtractor
 */
interface DataExtractorInterface
{
    public function extract(RequestInterface $request) : array;
}
