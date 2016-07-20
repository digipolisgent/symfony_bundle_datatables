<?php
namespace Avdb\DatatablesBundle;

use Avdb\DatatablesBundle\DependencyInjection\Compiler\DatatablesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class DatatablesBundle
 *
 * @package Avdb\DatatablesBundle
 */
class DatatablesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DatatablesCompilerPass());
    }
}
