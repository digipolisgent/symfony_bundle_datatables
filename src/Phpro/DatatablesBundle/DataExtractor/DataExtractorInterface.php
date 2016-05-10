<?php
declare(strict_types = 1);
namespace Phpro\DatatablesBundle\DataExtractor;

use Phpro\DatatablesBundle\Request\RequestInterface;

/**
 * Interface DataExtractorInterface
 *
 * @package Phpro\DatatablesBundle\DataExtractor
 */
interface DataExtractorInterface
{
    public function extract(RequestInterface $request) : ExtractionInterface;
}
