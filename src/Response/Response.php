<?php
namespace Avdb\DatatablesBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Response
 *
 * @package Avdb\DatatablesBundle\Response
 */
class Response extends JsonResponse
{
    /**
     * Response constructor.
     *
     * @param array $data
     * @param integer $totalRecords
     * @param integer $draw
     */
    public function __construct(array $data, $totalRecords, $draw)
    {
        parent::__construct([
            'data'              => $data,
            'draw'              => $draw,
            'recordsFiltered'   => $totalRecords,
            'recordsTotal'      => $totalRecords
        ], 200, []);
    }
}
