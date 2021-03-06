<?php

declare(strict_types=1);

/*
 * This file is part of the RollerworksSearch package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\Search\Elasticsearch;

use Rollerworks\Component\Search\Field\FieldConfig;

final class FieldMapping implements \Serializable
{
    public $fieldName;
    public $indexName;
    public $typeName;
    public $propertyName;
    public $nested = false;
    public $boost;
    public $options; // special options (reserved)

    /**
     * @var ValueConversion
     */
    public $valueConversion;

    /**
     * @var QueryConversion
     */
    public $queryConversion;

    public function __construct(string $fieldName, string $property, FieldConfig $fieldConfig)
    {
        $this->fieldName = $fieldName;

        $mapping = $this->parseProperty($property);
        $this->indexName = $mapping['indexName'];
        $this->typeName = $mapping['typeName'];
        $this->propertyName = $mapping['propertyName'];
        $this->nested = $mapping['nested'];

        $converter = $fieldConfig->getOption('elasticsearch_conversion');

        if ($converter instanceof ValueConversion) {
            $this->valueConversion = $converter;
        }
        if ($converter instanceof QueryConversion) {
            $this->queryConversion = $converter;
        }
    }

    /**
     * Supported formats:
     *      - <property>
     *      - <sub.property>
     *      - <nested[].property>
     *      - <sub.nested[].property>
     *      - <index>#<property>
     *      - <index>#<nested[].property>
     *      - <index>#<sub.nested[].property>
     *      - <index>/<type>#<property>
     *      - <index>/<type>#<sub.nested[].property>.
     *
     * @param string $property
     *
     * @return string[]
     */
    private function parseProperty(string $property): array
    {
        $indexName = null;
        $typeName = null;
        $propertyName = $property;
        $nested = false;
        if (false !== strpos($property, '#')) {
            [$path, $propertyName] = explode('#', $property);

            $path = trim($path, '/');
            $indexName = $path;
            if (false !== strpos($path, '/')) {
                [$indexName, $typeName] = explode('/', $path);
            }
        }

        if (false !== strpos($propertyName, '[]')) {
            $tokens = explode('[]', $propertyName);

            // last token is the property name
            $propertyName = trim(array_pop($tokens), '.');
            $propertyName = trim(end($tokens), '.').'.'.$propertyName;

            foreach ($tokens as $path) {
                $path = trim($path, '.');
                $nested = \compact('path', 'nested');
            }
        }

        return compact('indexName', 'typeName', 'propertyName', 'nested');
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(
            [
                'field_name' => $this->fieldName,
                'index_name' => $this->indexName,
                'type_name' => $this->typeName,
                'property_name' => $this->propertyName,
                'nested' => $this->nested,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        // no-op
    }
}
