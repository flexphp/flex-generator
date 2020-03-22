<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Constraint;

use FlexPHP\Generator\Domain\Builders\Constraint\RuleBuilder;
use FlexPHP\Generator\Tests\TestCase;

class RuleBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new RuleBuilder([
            'foo' => [
                'required' => true,
                'pattern' => '/^[a-z_]*$/',
                'length' => [
                    'min' => 20,
                    'max' => 100,
                ],
            ],
        ]);

        $this->assertEquals(\str_replace("\r\n", "\n", <<<T
    public function foo(array \$constraints = [])
    {
        return \$this->getValidator()->validate(__FUNCTION__, array_merge([
            new NotBlank(),
            new Regex([
                'pattern' => '/^[a-z_]*$/',
            ]),
            new Length([
                'min' => 20,
                'max' => 100,
            ]),
        ], \$constraints));
    }
T), $render->build());
    }
}
