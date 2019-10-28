<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Constraint\RuleBuilder;
use FlexPHP\Generator\Tests\TestCase;

class RuleBuilderTest extends TestCase
{
    public function testItOk()
    {
        $render = new RuleBuilder([
            'property' => 'foo',
            'constraints' => [
                'required' => true,
                'pattern' => '/^[a-z_]*$/',
                'required' => true,
                'length' => [
                    'min' => 20,
                    'max' => 100,
                ],
            ],
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<'T'
    public function foo(array $constraints = [])
    {
        return $this->validator->validate(__FUNCTION__, [
            new NotBlank(),
            new Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
            new Length([
                'min' => 20,
                'max' => 100,
            ]),
        ]);
    }
T), $render->build());
    }
}
