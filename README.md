# Generator

[![Latest Stable Version](https://poser.pugx.org/flexphp/generator/v/stable)](https://packagist.org/packages/flexphp/generator)
[![Total Downloads](https://poser.pugx.org/flexphp/generator/downloads)](https://packagist.org/packages/flexphp/generator)
[![Latest Unstable Version](https://poser.pugx.org/flexphp/generator/v/unstable)](https://packagist.org/packages/flexphp/generator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/flexphp/flex-generator/badges/quality-score.png)](https://scrutinizer-ci.com/g/flexphp/flex-generator)
[![License](https://poser.pugx.org/flexphp/generator/license)](https://packagist.org/packages/flexphp/generator)
[![composer.lock](https://poser.pugx.org/flexphp/generator/composerlock)](https://packagist.org/packages/flexphp/generator)

Flex PHP to Any Framework

Change between frameworks when you need. Keep It Simple, SOLID and DRY with FlexPHP.

## Installation

Install the package with Composer:

```bash
composer require flexphp/generator
```

## Configuration

First look up this [recommendations](https://symfony.com/doc/current/best_practices.html "Best Practices")

__Name__ (Required): Field Name, it is used in persistence (database, web services, etc), only accept: encoding=utf8, regex=[a-Z09_], maxlength=64.

> See more info [here](https://dev.mysql.com/doc/refman/8.0/en/identifiers.html "MySQL Reference").

__DataType__ (Required): Data Type used in validations.

> See more info [Doctrine Matrix Mapping Types](https://www.doctrine-project.org/projects/doctrine-dbal/en/2.9/reference/types.html#mapping-matrix "Doctrine Mapping Types Reference").

__Type__ (Optional|Default:text): Input Type used in [HTML forms](https://symfony.com/doc/current/reference/forms/types.html "Input Types for HTML"), [Commands](https://symfony.com/doc/current/console/input.html "Input Types for Command") and API/WS.

__Label__ (Optional|Default:Name): Label for input, it's used when data is show to "End User".

__Default__ (Optional): Default value for input in _create (C)_ context.

__Constraints__ (Optional): [Contraints](http://parsleyjs.org/doc/index.html#validators "Rules Available") applied for input in _create (C)_ and _update (U)_ context.

__Header__ (Optional|Default:Label?Name): Name use in header for input in _index (I)_ context.

__Align__ (Optional|Default:text=left,number=right): Align for input in _index (I)_ context.

__TypeShow__ (Optional|Default:text): Input Type used in _index (I)_ and _read (R)_ context.

__Format__ (Optional): Determine format to show value's input in context

__Help__ (Optional): Help message show to "End User" and _create (C)_, _update (U)_ context

__Context__ (Optional|Default:ICRUD): Define where input is used:
 - _(I)_: Index
 - _(C)_: Create
 - _(R)_: Read
 - _(U)_: Update
 - _(D)_: Delete


## License

Inputs is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
