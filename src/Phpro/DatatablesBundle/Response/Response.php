<?php
declare(strict_types = 1);
namespace Phpro\DatatablesBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Response
 *
 * @package Phpro\DatatablesBundle\Response
 */
class Response extends JsonResponse
{
    public function __construct(array $data, int $totalRecords, int $draw)
    {
        parent::__construct([
            'data'              => $data,
            'draw'              => $draw,
            'recordsFiltered'   => count($data),
            'recordsTotal'      => $totalRecords
        ], 200, []);
    }
}
