<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\Entity\EntityBuilder;
use FlexPHP\Generator\Domain\Builders\Entity\GetterBuilder;
use FlexPHP\Generator\Domain\Builders\Entity\SetterBuilder;
use FlexPHP\Generator\Tests\TestCase;

class EntityBuilderTest extends TestCase
{
    public function testItOk()
    {
        $name = 'Test';
        $getters = [
            'title' => (new GetterBuilder([
                'title' => [
                    'type' => 'string',
                ]
            ]))->build(),
            'content' => (new GetterBuilder([
                'content' => [
                    'type' => 'text',
                ],
            ]))->build(),
            'createdAt' => (new GetterBuilder([
                'createdAt' => [
                    'type' => 'datetime',
                ],
            ]))->build(),
            ];

            $setters = [
                'title' => (new SetterBuilder([
                    'title' => [
                        'type' => 'string',
                    ]
                ]))->build(),
                'content' => (new SetterBuilder([
                    'content' => [
                        'type' => 'text',
                    ],
                ]))->build(),
                'createdAt' => (new SetterBuilder([
                    'createdAt' => [
                        'type' => 'datetime',
                    ],
                ]))->build(),
                ];

        $render = new EntityBuilder([
            'name' => $name,
            'getters' => $getters,
            'setters' => $setters,
        ]);

        $this->assertEquals(str_replace("\r\n", "\n", <<<T
<?php

namespace Domain\Test\Entity;

/**
 * Entity Test.
 *
 * @author FlexPHP <flexphp@outlook.com>
 */
class Test
{
    public function getTitle(): string
    {
        return \$this->title;
    }

    public function getContent(): string
    {
        return \$this->content;
    }

    public function getCreatedAt(): string
    {
        return \$this->createdAt;
    }

    public function setTitle(string \$title): self
    {
        \$this->title = \$title;

        return \$this;
    }

    public function setContent(string \$content): self
    {
        \$this->content = \$content;

        return \$this;
    }

    public function setCreatedAt(string \$createdAt): self
    {
        \$this->createdAt = \$createdAt;

        return \$this;
    }
}

T
), $render->build());
    }
}
