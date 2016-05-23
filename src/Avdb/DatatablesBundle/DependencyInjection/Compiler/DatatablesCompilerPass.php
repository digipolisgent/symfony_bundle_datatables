<?php
namespace Avdb\DatatablesBundle\DependencyInjection\Compiler;

use Avdb\DatatablesBundle\DependencyInjection\Configuration;
use Avdb\DatatablesBundle\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DatatableCompilerPass
 * Registers the Datatables in the DatatableManager
 * 
 * @package Avdb\DatatablesBundle\DependencyInjection\Compiler
 */
class DatatablesCompilerPass implements CompilerPassInterface
{
    /**
     * Fetches all Datatables and adds them to the DatatableManager
     *
     * @param ContainerBuilder $container
     * @throws RuntimeException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(Configuration::DEFINITION_MANAGER)) {
            throw new RuntimeException(
                'DatatableManager class not found in service configuration. Please check : ' .
                Configuration::DEFINITION_MANAGER
            );
        }

        $manager = $container->findDefinition(Configuration::DEFINITION_MANAGER);
        $tables = $container->findTaggedServiceIds(Configuration::TAG_TABLE);

        foreach ($tables as $id => $tags) {
            $manager->addMethodCall('add', [new Reference($id)]);
        }
    }
}
