<?php

/**
 * This file is part of the RollerworksRecordFilterBundle.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rollerworks\RecordFilterBundle\Tests\Modifier;

use Rollerworks\RecordFilterBundle\Formatter\Formatter;
use Rollerworks\RecordFilterBundle\Formatter\Modifier\Validator;
use Rollerworks\RecordFilterBundle\Formatter\Modifier\DuplicateRemove;
use Rollerworks\RecordFilterBundle\Formatter\Modifier\ValuesToRange;
use Rollerworks\RecordFilterBundle\Formatter\Modifier\RangeNormalizer;
use Rollerworks\RecordFilterBundle\Formatter\Modifier\CompareNormalizer;
use Rollerworks\RecordFilterBundle\Formatter\Modifier\ValueOptimizer;

abstract class ModifierInterTestCase extends \Rollerworks\RecordFilterBundle\Tests\TestCase
{
    /**
     * @param boolean $loadModifiers
     * @return \Rollerworks\RecordFilterBundle\Formatter\Formatter
     */
    protected function newFormatter($loadModifiers = true)
    {
        $formatter = new Formatter($this->translator);

        if ($loadModifiers) {
            $formatter->registerModifier(new Validator());
            $formatter->registerModifier(new DuplicateRemove());
            $formatter->registerModifier(new RangeNormalizer());
            $formatter->registerModifier(new ValuesToRange());
            $formatter->registerModifier(new CompareNormalizer());
            $formatter->registerModifier(new ValueOptimizer());
        }

        return $formatter;
    }
}