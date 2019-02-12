<?php

namespace DigipolisGent\DatatablesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class DatatablesExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $key => $item) {
            $container->setParameter(Configuration::ROOT_NODE . ".$key", $item);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->loadDatatablesConfig($container);
    }

    /**
     * Loads all the datatable config files from other bundles.
     *
     * @param ContainerBuilder $container
     */
    private function loadDatatablesConfig(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        foreach ($bundles as $bundle) {
            $bundle = new \ReflectionClass($bundle);
            $path = dirname($bundle->getFileName()) . Configuration::PATH_CONFIG;

            if (!is_dir($path)) {
                continue;
            }

            $loader = new Loader\YamlFileLoader($container, new FileLocator($path));

            foreach (glob($path . '/*' . Configuration::SUFFIX_CONFIG) as $file) {
                $loader->load($file);
            }
        }
    }
}
