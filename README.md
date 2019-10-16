# Generator

<!-- [![Latest Stable Version](https://poser.pugx.org/flexphp/inputs/v/stable)](https://packagist.org/packages/flexphp/inputs) -->
<!-- [![Total Downloads](https://poser.pugx.org/flexphp/inputs/downloads)](https://packagist.org/packages/flexphp/inputs) -->
<!-- [![Latest Unstable Version](https://poser.pugx.org/flexphp/inputs/v/unstable)](https://packagist.org/packages/flexphp/inputs) -->
<!-- [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/flexphp/inputs/badges/quality-score.png)](https://scrutinizer-ci.com/g/flexphp/inputs) -->
<!-- [![License](https://poser.pugx.org/flexphp/inputs/license)](https://packagist.org/packages/flexphp/inputs) -->
<!-- [![composer.lock](https://poser.pugx.org/flexphp/inputs/composerlock)](https://packagist.org/packages/flexphp/inputs) -->

Flex PHP to Any Framework

Change between frameworks when you need. Keep It Simple, SOLID and DRY with FlexPHP.

## Installation

Install the package with Composer:

```bash
composer require flexphp/generator
```

## Configuration

First look up this [recommendations](https://symfony.com/doc/current/best_practices.html "Best Practices")

__Context__ (Optional): Tabla Name, it is used in persistence (database), only accept: encoding=utf8, regex=[a-Z09_], maxlength=64.

__Name__ (Required): Field Name, it is used in persistence (database, web services, etc), only accept: encoding=utf8, regex=[a-Z09_], maxlength=64.

> See more info [here](https://dev.mysql.com/doc/refman/8.0/en/identifiers.html "MySQL Reference").

__DataType__ (Required): Data Type used in validations.

> See more info [Doctrine Matrix Mapping Types](https://www.doctrine-project.org/projects/doctrine-dbal/en/2.9/reference/types.html#mapping-matrix "Doctrine Mapping Types Reference").

__InputType__ (Optional|Default:text): Input Type used in [HTML forms](https://symfony.com/doc/current/reference/forms/types.html "Input Types for HTML"), [Commands](https://symfony.com/doc/current/console/input.html "Input Types for Command") and API/WS.

__Label__ (Optional|Default:Name): Label for input, it's used when data is show to "End User".

__Default__ (Optional): Default value for input in _create (C)_ InputContext.

__Constraints__ (Optional): Contraints applied for input in _create (C)_ and _update (U)_ InputContext.

__InputName__ (Optional|Default:Name): Name input for input in HTML forms.

__InputAttributes__ (Optional): Attributes added in HTML forms.

__InputHeader__ (Optional|Default:Label?Name): Name use in header for input in _index (I)_ InputContext.

__InputAlign__ (Optional|Default:text=left,number=right): Input Types used in _index (I)_ InputContext.

__InputTypeShow__ (Optional|Default:text): Input Types used in _index (I)_ and _read (R)_ InputContext.

__InputFormat__ (Optional): Determine format to show value's input in InputContext

__InputHelp__ (Optional): Help message show to "End User" and _create (C)_, _update (U)_ InputContext

__InputContext__ (Optional|Default:ICRUD): Define where input is used _index (I)_, _create (C)_, _read (R)_, _update (U)_ and , _delete (D)_.


## License

Inputs is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
