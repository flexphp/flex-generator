<?php

namespace FlexPHP\Generator\Tests\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\Entity\EntityBuilder;
use FlexPHP\Generator\Tests\TestCase;

class EntityBuilderTest extends TestCase
{
    public function testItOk()
    {
        $name = 'Test';
        $properties = [
            'title' => [
                'type' => 'string',
            ],
            'content' => [
                'type' => 'text',
            ],
            'createdAt' => [
                'type' => 'datetime',
            ],
        ];

        $render = new EntityBuilder([
            'name' => $name,
            'properties' => $properties,
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
    private \$title;
    private \$content;
    private \$createdAt;

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
