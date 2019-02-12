<?php
namespace DigipolisGent\DatatablesBundle\Twig;

use DigipolisGent\DatatablesBundle\Column\ColumnInterface;
use DigipolisGent\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DatatableExtension
 *
 * @package DigipolisGent\DatatablesBundle\Twig
 */
class DatatablesExtension extends \Twig_Extension
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
        $resolver->setRequired('template');

        $this->options = $resolver->resolve($options);
    }

    /**
     * @return array
     */
    public function getFunctions()
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
        $columnAttributes = isset($column->getOptions()['attributes']) ? $column->getOptions()['attributes'] : [];

        foreach ($columnAttributes as $attribute => $value) {
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
            'uri'     => $this->router->generate('digipolisgent_datatables.data_api', [
                'alias'     => $table->getAlias(),
                '_locale'   => $this->getLocale($twig)
            ])
        ]);
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    private function getLocale(\Twig_Environment $twig)
    {
        /** @var AppVariable $app */
        $app = $twig->getGlobals()['app'];
        return $app->getRequest()->getLocale();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'digipolisgent_datatables';
    }
}
