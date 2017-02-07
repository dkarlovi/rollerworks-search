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

namespace Rollerworks\Component\Search\Extension\Doctrine\Dbal\Type;

use Rollerworks\Component\Search\Doctrine\Dbal\ColumnConversion;
use Rollerworks\Component\Search\Doctrine\Dbal\ValueConversion;
use Rollerworks\Component\Search\Field\AbstractFieldTypeExtension;
use Rollerworks\Component\Search\Field\FieldType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Allow to configure Doctrine ORM conversions.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 */
class FieldTypeExtension extends AbstractFieldTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            ['doctrine_dbal_conversion' => null]
        );

        $resolver->setAllowedTypes(
            'doctrine_dbal_conversion',
            [
                'null',
                \Closure::class,
                ColumnConversion::class,
                ValueConversion::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType(): string
    {
        return FieldType::class;
    }
}
