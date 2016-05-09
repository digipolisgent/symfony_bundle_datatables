<?php
declare(strict_types = 1);
namespace Phpro\DatatablesBundle\DependencyInjection\Compiler;

use Phpro\DatatablesBundle\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DatatableCompilerPass
 * Registers the Datatables in the DatatableManager
 * 
 * @package Phpro\DatatablesBundle\DependencyInjection\Compiler
 */
class DatatablesCompilerPass implements CompilerPassInterface
{
    const MANAGER_DEFINITION = 'phpro_datatables.manager';
    const TABLE_TAG          = 'phpro_datatables.table';

    /**
     * Fetches all Datatables and adds them to the DatatableManager
     *
     * @param ContainerBuilder $container
     * @throws RuntimeException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::MANAGER_DEFINITION)) {
            throw new RuntimeException(
                'DatatableManager class not found in service configuration. Please check : ' .
                self::MANAGER_DEFINITION
            );
        }

        $manager = $container->findDefinition(self::MANAGER_DEFINITION);
        $tables = $container->findTaggedServiceIds(self::TABLE_TAG);

        foreach ($tables as $id => $tags) {
            $manager->addMethodCall('add', [new Reference($id)]);
        }
    }
}
