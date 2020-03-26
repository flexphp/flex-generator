<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Entity;

use FlexPHP\Generator\Domain\Builders\Entity\EntityBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class EntityBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $name = 'Test';
        $properties = [
            'title' => [
                'DataType' => 'string',
            ],
            'content' => [
                'DataType' => 'text',
            ],
            'createdAt' => [
                'DataType' => 'datetime',
            ],
        ];

        $render = new EntityBuilder($name, $properties);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test\Entity;

use FlexPHP\Entities\Entity;

class Test extends Entity
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

    public function setTitle(string \$title): void
    {
        \$this->title = \$title;
    }

    public function setContent(string \$content): void
    {
        \$this->content = \$content;
    }

    public function setCreatedAt(string \$createdAt): void
    {
        \$this->createdAt = \$createdAt;
    }
}

T, $render->build());
    }
}
