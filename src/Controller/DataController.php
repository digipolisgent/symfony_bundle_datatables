<?php
namespace Avdb\DatatablesBundle\Controller;

use Avdb\DatatablesBundle\Manager\DatatableManagerInterface;
use Avdb\DatatablesBundle\Request\Request;
use Avdb\DatatablesBundle\Response\Response;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class DataController
 * Returns the data for each Datatable
 * 
 * @package Avdb\DatatablesBundle\Controller
 */
class DataController
{
    /**
     * @var DatatableManagerInterface
     */
    private $manager;

    /**
     * DataController constructor.
     *
     * @param DatatableManagerInterface $manager
     */
    public function __construct(DatatableManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Generates a page of data for the Datatable
     *
     * @param HttpRequest $request
     * @param $alias
     * @return Response
     */
    public function dataAction(HttpRequest $request, $alias)
    {
        $table = $this->manager->get($alias);

        return $table->buildResponse(new Request($request));
    }
}
