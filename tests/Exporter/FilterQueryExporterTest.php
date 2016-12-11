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

namespace Rollerworks\Component\Search\Tests\Exporter;

use Rollerworks\Component\Search\Exporter\FilterQueryExporter;
use Rollerworks\Component\Search\ExporterInterface;
use Rollerworks\Component\Search\FieldConfigInterface;
use Rollerworks\Component\Search\Input\FilterQueryInput;
use Rollerworks\Component\Search\Input\ProcessorConfig;
use Rollerworks\Component\Search\InputProcessorInterface;
use Rollerworks\Component\Search\SearchCondition;
use Rollerworks\Component\Search\Test\SearchConditionExporterTestCase;
use Rollerworks\Component\Search\Value\ValuesBag;
use Rollerworks\Component\Search\Value\ValuesGroup;

final class FilterQueryExporterTest extends SearchConditionExporterTestCase
{
    /**
     * @test
     */
    public function it_exporters_with_field_label()
    {
        $labelResolver = function (FieldConfigInterface $field) {
            $name = $field->getName();

            if ($name === 'name') {
                return 'firstname';
            }

            return $name;
        };

        $exporter = $this->getExporter($labelResolver);
        $config = new ProcessorConfig($this->getFieldSet());

        $expectedGroup = new ValuesGroup();

        $values = new ValuesBag();
        $values->addSimpleValue('value');
        $values->addSimpleValue('value2');

        $expectedGroup->addField('name', $values);

        $condition = new SearchCondition($config->getFieldSet(), $expectedGroup);
        self::assertExportEquals('firstname: value, value2;', $exporter->exportCondition($condition));

        $processor = $this->getInputProcessor($labelResolver);
        $processor->process($config, 'firstname: value, value2;');
    }

    public function provideSingleValuePairTest()
    {
        return 'name: "value ", "-value2", "value2-", "10.00", "10,00", hÌ, ٤٤٤٦٥٤٦٠٠, "doctor""who""""", !value3;';
    }

    public function provideMultipleValuesTest()
    {
        return 'name: value, value2; date: "12-16-2014";';
    }

    public function provideRangeValuesTest()
    {
        return 'id: 1-10, 15-30, ]100-200, 310-400[, !50-70; date: "12-16-2014"-"12-20-2014";';
    }

    public function provideComparisonValuesTest()
    {
        return 'id: >1, <2, <=5, >=8; date: >="12-16-2014";';
    }

    public function provideMatcherValuesTest()
    {
        return 'name: ~*value, ~i>value2, ~<value3, ~?"^foo|bar?", ~!*value4, ~i!*value5, ~=value9, ~!=value10, ~i=value11, ~i!=value12;';
    }

    public function provideGroupTest()
    {
        return 'name: value, value2; (name: value3, value4); *(name: value8, value10);';
    }

    public function provideMultipleSubGroupTest()
    {
        return '(name: value, value2); (name: value3, value4);';
    }

    public function provideNestedGroupTest()
    {
        return '((name: value, value2));';
    }

    public function provideEmptyValuesTest()
    {
        return '';
    }

    public function provideEmptyGroupTest()
    {
        return '();';
    }

    protected function getExporter(callable $labelResolver = null): ExporterInterface
    {
        return new FilterQueryExporter($labelResolver);
    }

    protected function getInputProcessor(callable $labelResolver = null): InputProcessorInterface
    {
        return new FilterQueryInput($labelResolver);
    }
}
