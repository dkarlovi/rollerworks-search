<?php

/*
 * This file is part of the RollerworksSearch package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\Search;

/**
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 */
interface SearchFactoryInterface
{
    /**
     * Create a new search field.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     * @param bool   $required
     *
     * @return FieldConfigInterface
     */
    public function createField($name, $type, array $options = array(), $required = false);

    /**
     * Create a new FieldsetBuilderInterface instance.
     *
     * @param string $name
     *
     * @return FieldsetBuilder Interface
     */
    public function createFieldSetBuilder($name);
}