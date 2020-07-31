<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\FormType;

use FlexPHP\Generator\Domain\Builders\FormType\FormTypeBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;

final class FormTypeBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new FormTypeBuilder(new Schema('Test', 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
    }
}

T
, $render->build());
    }

    public function testItOkWithDiffNameProperties(): void
    {
        $render = new FormTypeBuilder($this->getSchema());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('lower', InputType\TextType::class, [
            'label' => 'Lower',
            'required' => true,
            'attr' => [
                'minlength' => 20,
                'maxlength' => 100,
            ],
        ]);
        \$builder->add('upper', InputType\IntegerType::class, [
            'label' => 'Upper',
            'required' => false,
            'attr' => [
                'min' => 2,
                'max' => 10,
            ],
        ]);
        \$builder->add('pascalCase', InputType\DateTimeType::class, [
            'label' => 'Pascal Case',
            'required' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'format' => 'Y-m-d H:i:s',
        ]);
        \$builder->add('camelCase', InputType\CheckboxType::class, [
            'label' => 'Camel Case',
            'required' => false,
        ]);
        \$builder->add('snakeCase', InputType\TextareaType::class, [
            'label' => 'Snake Case',
            'required' => false,
            'attr' => [
                'minlength' => 100,
                'maxlength' => 200,
            ],
        ]);
    }
}

T
, $render->build());
    }

    public function testItFkRelationsOk(): void
    {
        $render = new FormTypeBuilder($this->getSchemaFkRelation());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use App\Form\Type\Select2Type;
use Doctrine\DBAL\Connection;
use Domain\Bar\UseCase\ReadBarUseCase;
use Domain\Bar\BarRepository;
use Domain\Bar\Gateway\MySQLBarGateway;
use Domain\Bar\Request\ReadBarRequest;
use Domain\Post\UseCase\ReadPostUseCase;
use Domain\Post\PostRepository;
use Domain\Post\Gateway\MySQLPostGateway;
use Domain\Post\Request\ReadPostRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TestFormType extends AbstractType
{
    private \$conn;
    private \$router;

    public function __construct(Connection \$conn, UrlGeneratorInterface \$router)
    {
        \$this->conn = \$conn;
        \$this->router = \$router;
    }

    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$fooModifier = function (FormInterface \$form, ?string \$value) {
            \$choices = null;

            if (!empty(\$value)) {
                \$useCase = new ReadBarUseCase(new BarRepository(new MySQLBarGateway(\$this->conn)));
                \$response = \$useCase->execute(new ReadBarRequest(\$value));

                if (\$response->bar->baz()) {
                    \$choices = [\$response->bar->fuz() => \$value];
                }
            }

            \$form->add('foo', Select2Type::class, [
                'label' => 'Foo',
                'required' => true,
                'attr' => [
                    'data-autocomplete-url' => \$this->router->generate('tests.find.bars'),
                ],
                'choices' => \$choices,
                'data' => \$value,
            ]);
        };

        \$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent \$event) use (\$fooModifier) {
            if (!\$event->getData()) {
                return null;
            }

            \$fooModifier(\$event->getForm(), \$event->getData()->foo());
        });

        \$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent \$event) use (\$fooModifier) {
            \$fooModifier(\$event->getForm(), (string)\$event->getData()['foo'] ?? null);
        });

        \$postIdModifier = function (FormInterface \$form, ?int \$value) {
            \$choices = null;

            if (!empty(\$value)) {
                \$useCase = new ReadPostUseCase(new PostRepository(new MySQLPostGateway(\$this->conn)));
                \$response = \$useCase->execute(new ReadPostRequest(\$value));

                if (\$response->post->id()) {
                    \$choices = [\$response->post->name() => \$value];
                }
            }

            \$form->add('postId', Select2Type::class, [
                'label' => 'Post Id',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => \$this->router->generate('tests.find.posts'),
                ],
                'choices' => \$choices,
                'data' => \$value,
            ]);
        };

        \$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent \$event) use (\$postIdModifier) {
            if (!\$event->getData()) {
                return null;
            }

            \$postIdModifier(\$event->getForm(), \$event->getData()->postId());
        });

        \$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent \$event) use (\$postIdModifier) {
            \$postIdModifier(\$event->getForm(), (int)\$event->getData()['postId'] ?? null);
        });

        \$builder->add('foo', Select2Type::class, [
            'label' => 'Foo',
            'required' => true,
            'attr' => [
                'data-autocomplete-url' => \$this->router->generate('tests.find.bars'),
            ],
            'choices' => [],
        ]);
        \$builder->add('postId', Select2Type::class, [
            'label' => 'Post Id',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => \$this->router->generate('tests.find.posts'),
            ],
            'choices' => [],
        ]);
    }
}

T
, $render->build());
    }

    public function testItAutoIncrementalAndBlameable(): void
    {
        $render = new FormTypeBuilder($this->getSchemaAiAndBlame());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('value', InputType\IntegerType::class, [
            'label' => 'Value',
            'required' => true,
        ]);
    }
}

T
, $render->build());
    }

    public function testItBlameBy(): void
    {
        $render = new FormTypeBuilder($this->getSchemaStringAndBlameBy());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use App\Form\Type\Select2Type;
use Doctrine\DBAL\Connection;
use Domain\User\UseCase\ReadUserUseCase;
use Domain\User\UserRepository;
use Domain\User\Gateway\MySQLUserGateway;
use Domain\User\Request\ReadUserRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TestFormType extends AbstractType
{
    private \$conn;
    private \$router;

    public function __construct(Connection \$conn, UrlGeneratorInterface \$router)
    {
        \$this->conn = \$conn;
        \$this->router = \$router;
    }

    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('code', InputType\TextType::class, [
            'label' => 'Code',
            'required' => true,
        ]);
        \$builder->add('name', InputType\TextareaType::class, [
            'label' => 'Name',
            'required' => true,
        ]);
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffNameEntity(string $entity, string $expected): void
    {
        $render = new FormTypeBuilder(new Schema($entity, 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected};

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;

final class {$expected}FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'Userpassword'],
            ['USERPASSWORD', 'Userpassword'],
            ['UserPassword', 'UserPassword'],
            ['userPassword', 'UserPassword'],
            ['user_password', 'UserPassword'],
            ['user-password', 'UserPassword'],
            ['Posts', 'Post'],
        ];
    }
}
