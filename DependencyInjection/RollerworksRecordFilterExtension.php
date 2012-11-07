<?php

/**
 * This file is part of the RollerworksRecordFilterBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rollerworks\Bundle\RecordFilterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * RecordFilter configuration.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 */
class RollerworksRecordFilterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $config);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $cacheDirectory = $container->getParameterBag()->resolveValue($config['metadata_cache']);

        if (!is_dir($cacheDirectory)) {
            mkdir($cacheDirectory, 0777, true);
        }

        // the cache directory should be the first argument of the cache service
        $container->getDefinition('rollerworks_record_filter.metadata.cache')->replaceArgument(0, $cacheDirectory);

        $container->setParameter('rollerworks_record_filter.filters_directory', $config['filters_directory']);
        $container->setParameter('rollerworks_record_filter.filters_namespace', $config['filters_namespace']);

        $container->setParameter('rollerworks_record_filter.factories.fieldset.auto_generate', $config['factories']['fieldset']['auto_generate']);
        $container->setParameter('rollerworks_record_filter.factories.fieldset.namespace', $config['factories']['fieldset']['namespace']);
        $container->setParameter('rollerworks_record_filter.factories.fieldset.label_translator_prefix', $config['factories']['fieldset']['label_translator_prefix']);
        $container->setParameter('rollerworks_record_filter.factories.fieldset.label_translator_domain', $config['factories']['fieldset']['label_translator_domain']);

        $container->setParameter('rollerworks_record_filter.fieldsets', serialize($config['fieldsets']));

        if (isset($config['doctrine']['orm'])) {
            $loader->load('doctrine.orm.xml');

            $container->getDefinition('rollerworks_record_filter.doctrine.orm.where_builder')
                ->addMethodCall('setEntityManager', array(new Reference(sprintf('doctrine.orm.%s_entity_manager', $container->getParameterBag()->resolveValue($config['doctrine']['orm']['default_entity_manager'])))));

            if (isset($config['factories']['doctrine']['orm'])) {
                $container->setParameter('rollerworks_record_filter.factories.doctrine.orm.wherebuilder.auto_generate', $config['factories']['doctrine']['orm']['wherebuilder']['auto_generate']);
                $container->setParameter('rollerworks_record_filter.factories.doctrine.orm.wherebuilder.namespace', $config['factories']['doctrine']['orm']['wherebuilder']['namespace']);

                $container->getDefinition('rollerworks_record_filter.doctrine.orm.wherebuilder_factory')
                    ->addMethodCall('setEntityManager', array(new Reference(sprintf('doctrine.orm.%s_entity_manager', $container->getParameterBag()->resolveValue($config['factories']['doctrine']['orm']['wherebuilder']['default_entity_manager'])))));
            }
        }
    }
}
