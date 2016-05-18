<?php

namespace AppBundle\Datatables\Generator;

use Phpro\DatatablesBundle\Column\ColumnInterface;
use Phpro\DatatablesBundle\Exception\RuntimeException;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\Templating\EngineInterface;

class FactoryGenerator
{
    /**
     * @var EngineInterface
     */
    private $renderer;

    /**
     * @var string
     */
    private $filePath = '/Datatables/Factory';

    const FACTORY_TEMPLATE = 'DatatablesBundle:templates:factory.php.twig';
    const CREATE_COLUMN_METHOD_TEMPLATE = 'DatatablesBundle:templates:create-column.php.twig';

    /**
     * FactoryGenerator constructor.
     *
     * @param EngineInterface $renderer
     */
    public function __construct(EngineInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param BundleInterface $bundle
     * @param string $name
     * @param array|ColumnInterface[] $columns
     * @throws RuntimeException
     */
    public function generateFactoryClass(BundleInterface $bundle, $name, $columns)
    {
        $path = $bundle->getPath() . $this->filePath;

        if(!@mkdir($path, 0777, true) && !is_dir($path)) {
            throw new RuntimeException("Could not create path $path");
        }

        $file = $path . '/' . ucfirst($name) . 'DatatableFactory.php';
        $createColumnMethods = '';

        foreach($columns as $column) {
            $createColumnMethods .= $this->renderer->render(self::CREATE_COLUMN_METHOD_TEMPLATE, [
                'name' => $column->getName(),
                'label' => $column->getLabel(),
                'property' => $column->getOptions()['property']
            ]);
        }

        $content = $this->renderer->render(self::FACTORY_TEMPLATE, [
            'bundle' => ucfirst($bundle->getNamespace()),
            'ucName' => ucfirst($name),
            'lcName' => strtolower($name),
            'columns'=> $createColumnMethods
        ]);

        return file_put_contents($file, $content);
    }
}
