RollerworksSearch Symfony Validator
===================================

[![Build Status](https://secure.travis-ci.org/rollerworks/search-symfony-validator.svg?branch=master)](http://travis-ci.org/rollerworks/rollerworks-search-symfony-validator)

The [RollerworksSearch][1] Symfony Validator facilitates the validating of input values
using the [Symfony Validator component][2].

**Note:**

>This validation is meant to be used for business rules like a minimum/maximum
> value range or disallowing specific patterns. The data transformers already ensure
> the value is properly transformed.

Installation
------------

To install this extension, add the `rollerworks/search-symfony-validator`
package in your composer.json and update your dependencies.

```bash
$ composer require rollerworks/search-symfony-validator
```

Next you need to enable the `Rollerworks\Component\Search\Extension\Symfony\Validator\ValidatorExtension`
in the `SearchFactoryBuilder` and pass the Input Validator to your Input Processor.

```php
use Rollerworks\Component\Search\Searches;
use Rollerworks\Component\Search\Extension\Validator\ValidatorExtension;
use Rollerworks\Component\Search\Extension\Validator\InputValidator;
use Rollerworks\Component\Search\Input\StringQueryInput;
use Symfony\Component\Validator\Validation;

$searchFactory = Searches::createSearchFactoryBuilder();
    ->addExtension(new ValidatorExtension())
    // ...

    ->getSearchFactory();

$validatorBuilder = Validation::createValidatorBuilder();
$validator = $validatorBuilder->getValidator();

$inputProcessor = new StringQueryInput(new InputValidator($validator));
```

That's it, you can now use the Validator. But note only search fields with
`constraints` set will be actually validated by the validator.

Setting validation constraints
------------------------------

> Before you continue make sure you have a good understanding of what Constraints
> are and how they are to be used. See [Symfony Validator component][2] for
> more information.

You can configure the constraint on a per-field basis when building your FieldSet:

```php
use Symfony\Component\Validator\Constraints as Assert;
use Rollerworks\Component\Search\Extension\Core\Type\IntegerType;

// ..

$fieldSetBuilder = $searchFactory->createFieldSetBuilder()
$fieldSetBuilder->add('id', IntegerType::class, ['constraints' => new Assert\Range(['min' => 5])]);
```

Or when your (custom) type always needs these specific constraints make the constraints
part of the field type using the `configureOptions` method of the field type. Using:

```php
public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults(
        [
            'constraints' => new Assert\Length(array('min' => 101)),
        ]
    );
}
```

Symfony framework integration
-----------------------------

**Note:** The Symfony integration bundle for RollerworksSearch already enables
the Symfony validator service. You don't need to do anything but configure your
field's constraints.

License
-------

The source of this package is subject to the MIT license that is bundled
with this source code in the file [LICENSE](LICENSE).

If you'd like to contribute to this project, please see the [RollerworksSearch contributing guide][3].

[1]: https://github.com/rollerworks/RollerworksSearch
[2]: http://symfony.com/doc/current/validation.html
[3]: https://github.com/rollerworks/RollerworksSearch#contributing
