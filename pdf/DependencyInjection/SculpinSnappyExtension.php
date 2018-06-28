<?php
/**
 * This file is part of Sculpin Snappy Bundle.
 *
 * (c) Eternal Apps
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EternalApps\Sculpin\Bundle\SnappyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Sculpin Snappy Extension.
 *
 * @author Krzysztof Janda <k.janda@eternalapps.pl>
 */
class SculpinSnappyExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        if ($config['pdf']['enabled']) {
            $loader->load('pdf.xml');

            $container->setParameter('sculpin_snappy.pdf.binary', $config['pdf']['binary']);
            $container->setParameter('sculpin_snappy.pdf.options', $config['pdf']['options']);

            $container->findDefinition('sculpin_snappy.pdf')
                ->addArgument($config['pdf']['binary'])
                ->addArgument($config['pdf']['options']);
        }
    }
}
