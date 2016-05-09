<?php
declare(strict_types = 1);
namespace phpro\DatatablesBundle\Request;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class Request
 *
 * @package phpro\DatatablesBundle\Request
 */
class Request implements RequestInterface
{
    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * Default request parameters
     *
     * @var array
     */
    protected $defaults = [
        'order'     => 'desc',
        'sort'      => 'id',
        'page_size' => 10,
    ];

    /**
     * Request constructor.
     *
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest() : HttpRequest
    {
        return $this->request;
    }

    /**
     * Get Page size from request
     *
     * @return integer
     */
    public function getPageSize() : int
    {
        return (int)$this->request->query->get('length', $this->defaults['page_size']);
    }

    /**
     * Get page number from request
     *
     * @return int
     */
    public function getPage() : int
    {
        $offset = $this->request->query->get('start', $this->defaults['page_size']);
        $size = $this->getPageSize();

        return (int)floor($offset / $size) + 1;
    }

    /**
     * Returns the offset of data requested
     * 
     * @return int
     */
    public function getOffset() : int
    {
        return $this->request->query->get('start', 0);
    }

    /**
     * Extracts the sort parameter from the request object
     *
     * @return string
     */
    public function getSort() : string
    {
        $columnIndex = $this->request->query->get('order', [])[0]['column'] ?? false;
        $sort = $this->defaults['sort'];

        if (false === $columnIndex) {
            return $sort;
        }

        $columns = $this->request->query->get('columns', []);
        foreach ($columns as $i => $column) {
            if ($i === $columnIndex && $column['orderable'] !== 'false') {
                $sort = $column['name'];
            }
        }

        return (string)$sort;
    }

    /**
     * Extracts the order parameter from the request object
     *
     * @return string
     */
    public function getOrder() : string
    {
        return (string)($this->request->query->get('order', [])[0]['dir'] ?? $this->defaults['order']);
    }

    /**
     * Extracts the search parameter from the request object
     *
     * @return string
     */
    public function getSearch() : string
    {
        return (string)($this->request->query->get('search', [])['value'] ?? null);
    }

    /**
     * @return int
     */
    public function getDraw() : int
    {
        return (int)$this->request->query->get('draw', 0);
    }
}
