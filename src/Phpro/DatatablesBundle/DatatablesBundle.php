<?php
namespace Phpro\DatatablesBundle;

use Phpro\DatatablesBundle\DependencyInjection\Compiler\DatatableCompilerPass;
use Phpro\DatatablesBundle\DependencyInjection\Compiler\DatatablesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class phproDatatablesBundle
 *
 * @package Phpro\DatatablesBundle
 */
class DatatablesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DatatablesCompilerPass());
    }
}
