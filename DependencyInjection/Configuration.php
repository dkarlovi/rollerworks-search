<?php

/**
 * This file is part of the RollerworksRecordFilterBundle package.
 *
 * (c) Rollerscapes
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link    http://projects.rollerscapes.net/RollerFramework
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 */

namespace Rollerworks\RecordFilterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('rollerworks_record_filter')
            ->children()
                ->scalarNode('filters_namespace')->defaultValue('RecordFilter')->end()
                ->scalarNode('filters_directory')->defaultValue('%kernel.cache_dir%/record_filters')->end()

                ->booleanNode('generate_formatters')->defaultValue(false)->end()
                ->booleanNode('generate_sqlstructs')->defaultValue(false)->end()
                ->booleanNode('generate_querybuilders')->defaultValue(false)->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}

