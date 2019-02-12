<?php
namespace DigipolisGent\DatatablesBundle;

use DigipolisGent\DatatablesBundle\DependencyInjection\Compiler\DatatablesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class DatatablesBundle
 *
 * @package DigipolisGent\DatatablesBundle
 */
class DatatablesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DatatablesCompilerPass());
    }
}
