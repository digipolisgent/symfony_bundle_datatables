<?php
namespace Avdb\DatatablesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    const ROOT_NODE = 'avdb_datatables';
    const PATH_FACTORY   = '/Datatables/Factory';
    const PATH_EXTRACTOR = '/Datatables/DataExtractor';
    const PATH_CONFIG    = '/Resources/config/datatables';
    const SUFFIX_FACTORY   = 'DatatableFactory.php';
    const SUFFIX_EXTRACTOR = 'DataExtractor.php';
    const SUFFIX_CONFIG    = '.datatable.yml';
    const NAMESPACE_FACTORY   = '%s\Datatables\Factory\%sDatatableFactory';
    const NAMESPACE_EXTRACTOR = '%s\Datatables\DataExtractor\%sDataExtractor';
    const DEFINITION_MANAGER = 'avdb_datatables.manager';
    const TAG_TABLE = 'avdb_datatables.table';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(self::ROOT_NODE);

        $rootNode->children()
            ->scalarNode('table_template')
            ->defaultValue('@Datatables/Datatables/default_table.html.twig')
            ->end();

        return $treeBuilder;
    }
}
