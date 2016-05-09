<?php
declare(strict_types = 1);
namespace Phpro\DatatablesBundle\Twig;

use Phpro\DatatablesBundle\Column\ColumnInterface;
use Phpro\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DatatableExtension
 *
 * @package Phpro\DatatablesBundle\Twig
 */
class DatatableExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $options;

    /**
     * @var RouterInterface
     */
    private $router;

    const DEFAULT_TEMPLATE = 'DatatablesBundle:DataTable:default_table.html.twig';

    /**
     * DatatableExtension constructor.
     *
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param array $options
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router,  array $options = [])
    {
        $this->translator = $translator;
        $this->router = $router;
        $resolver = new OptionsResolver();
        $resolver->setDefault('template', self::DEFAULT_TEMPLATE);
        $this->options = $resolver->resolve($options);
    }

    /**
     * @return array
     */
    public function getFunctions() : array
    {
        return [
            'render_table'  => new \Twig_SimpleFunction('datatables_render_table', [$this, 'renderTable'], [
                'is_safe'           => ['html'],
                'needs_environment' => true,
            ]),
            'render_column' => new \Twig_SimpleFunction('datatables_render_column', [$this, 'renderColumn'], [
                'is_safe'           => ['html'],
                'needs_environment' => false,
            ]),
        ];
    }

    /**
     * Renders a single column header
     *
     * @param ColumnInterface $column
     * @return string
     */
    public function renderColumn(ColumnInterface $column)
    {
        $attributes = [];

        foreach ($column->getOptions()['attributes'] ?? [] as $attribute => $value) {
            $attributes[] = "$attribute=\"$value\"";
        }

        $attributes = implode(' ', $attributes);

        return "<th $attributes>" . $this->translator->trans($column->getLabel()) . '</th>';
    }

    /**
     * Renders the Datatable object
     *
     * @param \Twig_Environment $twig
     * @param DatatableInterface $table
     * @param array $options
     * @return string
     */
    public function renderTable(\Twig_Environment $twig, DatatableInterface $table, array $options = [])
    {
        $options = array_merge($this->options, $options);

        return $twig->render($options['template'], [
            'table'   => $table,
            'options' => $options,
            'uri'     => $this->router->generate('phpro_datatables.data_api', ['alias' => $table->getAlias()])
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'phpro_datatables';
    }
}
