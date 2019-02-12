<?php 
namespace DigipolisGent\DatatablesBundle\Request;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class Request
 *
 * @package DigipolisGent\DatatablesBundle\Request
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
    protected static $defaults = [
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
     * @inheritdoc
     */
    public function getHttpRequest()
    {
        return $this->request;
    }

    /**
     * @inheritdoc
     */
    public function getPageSize($default = null)
    {
        if(null === $default) {
            $default = self::$defaults['page_size'];
        }

        return (int)$this->request->query->get('length', (int)$default);
    }

    /**
     * @inheritdoc
     */
    public function getPage()
    {
        $offset = $this->request->query->get('start', 0);
        $size = $this->getPageSize();

        return (int)floor($offset / $size) + 1;
    }

    /**
     * @inheritdoc
     */
    public function getOffset()
    {
        return $this->request->query->get('start', 0);
    }

    /**
     * @inheritdoc
     */
    public function getSort($default = null)
    {
        $order = $this->request->query->get('order', []);

        $columnIndex = isset($order[0]['column']) ? $order[0]['column'] : false;

        if (false === $columnIndex) {
            return $default ?: self::$defaults['sort'];
        }

        $columns = $this->request->query->get('columns', []);

        if (isset($columns[$columnIndex]['name'])) {
            $sort = $columns[$columnIndex]['name'];
        }else{
            return $default ?: self::$defaults['sort'];
        }

        return (string)$sort;
    }

    /**
     * @inheritdoc
     */
    public function getOrder($default = null)
    {
        $order = $this->request->query->get('order', []);

        if(isset($order[0]['dir'])) {
            return $order[0]['dir'];
        }

        return $default ?: self::$defaults['order'];
    }

    /**
     * @inheritdoc
     */
    public function getSearch()
    {
        $search = $this->request->query->get('search', []);
        return isset($search['value']) ? $search['value'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getDraw()
    {
        return (int)$this->request->query->get('draw', 0);
    }
}
