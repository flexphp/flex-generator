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
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
    }

    public function configureOptions(OptionsResolver \$resolver)
    {
        \$resolver->setDefaults([
            'translation_domain' => 'test',
        ]);
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
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('lower', InputType\TextType::class, [
            'label' => 'label.lower',
            'required' => true,
            'attr' => [
                'minlength' => 20,
                'maxlength' => 100,
            ],
        ]);
        \$builder->add('upper', InputType\IntegerType::class, [
            'label' => 'label.upper',
            'required' => false,
            'attr' => [
                'min' => 2,
                'max' => 10,
            ],
        ]);
        \$builder->add('pascalCase', InputType\DateTimeType::class, [
            'label' => 'label.pascalCase',
            'required' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'format' => 'Y-m-d H:i:s',
        ]);
        \$builder->add('camelCase', InputType\CheckboxType::class, [
            'label' => 'label.camelCase',
            'required' => false,
        ]);
        \$builder->add('snakeCase', InputType\TextareaType::class, [
            'label' => 'label.snakeCase',
            'required' => false,
            'attr' => [
                'minlength' => 100,
                'maxlength' => 200,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver \$resolver)
    {
        \$resolver->setDefaults([
            'translation_domain' => 'test',
        ]);
    }
}

T
, $render->build());
    }

    public function testItFkRelationsOk(): void
    {
        $render = new FormTypeBuilder($this->getSchemaFkRelation('PostComments'));

        $this->assertEquals(<<<'T'
<?php declare(strict_types=1);

namespace Domain\PostComment;

use App\Form\Type\Select2Type;
use Doctrine\DBAL\Connection;
use Domain\Bar\BarRepository;
use Domain\Bar\Gateway\MySQLBarGateway;
use Domain\Bar\Request\ReadBarRequest;
use Domain\Bar\UseCase\ReadBarUseCase;
use Domain\Post\Gateway\MySQLPostGateway;
use Domain\Post\PostRepository;
use Domain\Post\Request\ReadPostRequest;
use Domain\Post\UseCase\ReadPostUseCase;
use Domain\UserStatus\Gateway\MySQLUserStatusGateway;
use Domain\UserStatus\Request\ReadUserStatusRequest;
use Domain\UserStatus\UseCase\ReadUserStatusUseCase;
use Domain\UserStatus\UserStatusRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PostCommentFormType extends AbstractType
{
    private $conn;
    private $router;

    public function __construct(Connection $conn, UrlGeneratorInterface $router)
    {
        $this->conn = $conn;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $fooModifier = function (FormInterface $form, ?string $value) {
            $choices = null;

            if (!empty($value)) {
                $useCase = new ReadBarUseCase(new BarRepository(new MySQLBarGateway($this->conn)));
                $response = $useCase->execute(new ReadBarRequest($value));

                if ($response->bar->baz()) {
                    $choices = [$response->bar->fuz() => $value];
                }
            }

            $form->add('foo', Select2Type::class, [
                'label' => 'label.foo',
                'required' => true,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('post-comments.find.bars'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($fooModifier) {
            if (!$event->getData()) {
                return null;
            }

            $fooModifier($event->getForm(), $event->getData()->foo());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($fooModifier) {
            $fooModifier($event->getForm(), (string)$event->getData()['foo'] ?: null);
        });

        $postIdModifier = function (FormInterface $form, ?int $value) {
            $choices = null;

            if (!empty($value)) {
                $useCase = new ReadPostUseCase(new PostRepository(new MySQLPostGateway($this->conn)));
                $response = $useCase->execute(new ReadPostRequest($value));

                if ($response->post->id()) {
                    $choices = [$response->post->name() => $value];
                }
            }

            $form->add('postId', Select2Type::class, [
                'label' => 'label.postId',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('post-comments.find.posts'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($postIdModifier) {
            if (!$event->getData()) {
                return null;
            }

            $postIdModifier($event->getForm(), $event->getData()->postId());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($postIdModifier) {
            $postIdModifier($event->getForm(), (int)$event->getData()['postId'] ?: null);
        });

        $statusIdModifier = function (FormInterface $form, ?int $value) {
            $choices = null;

            if (!empty($value)) {
                $useCase = new ReadUserStatusUseCase(new UserStatusRepository(new MySQLUserStatusGateway($this->conn)));
                $response = $useCase->execute(new ReadUserStatusRequest($value));

                if ($response->userStatus->id()) {
                    $choices = [$response->userStatus->name() => $value];
                }
            }

            $form->add('statusId', Select2Type::class, [
                'label' => 'label.statusId',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('post-comments.find.user-status'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($statusIdModifier) {
            if (!$event->getData()) {
                return null;
            }

            $statusIdModifier($event->getForm(), $event->getData()->statusId());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($statusIdModifier) {
            $statusIdModifier($event->getForm(), (int)$event->getData()['statusId'] ?: null);
        });

        $builder->add('foo', Select2Type::class, [
            'label' => 'label.foo',
            'required' => true,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('post-comments.find.bars'),
            ],
        ]);
        $builder->add('postId', Select2Type::class, [
            'label' => 'label.postId',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('post-comments.find.posts'),
            ],
        ]);
        $builder->add('statusId', Select2Type::class, [
            'label' => 'label.statusId',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('post-comments.find.user-status'),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'postComment',
        ]);
    }
}

T
, $render->build());
    }

    public function testItAiAndBlameAt(): void
    {
        $render = new FormTypeBuilder($this->getSchemaAiAndBlameAt());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\Test;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('value', InputType\IntegerType::class, [
            'label' => 'label.value',
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver \$resolver)
    {
        \$resolver->setDefaults([
            'translation_domain' => 'test',
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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('code', InputType\TextType::class, [
            'label' => 'label.code',
            'required' => true,
        ]);
        \$builder->add('name', InputType\TextareaType::class, [
            'label' => 'label.name',
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver \$resolver)
    {
        \$resolver->setDefaults([
            'translation_domain' => 'test',
        ]);
    }
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffNameEntity(string $entity, string $expected, string $item): void
    {
        $render = new FormTypeBuilder(new Schema($entity, 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace Domain\\{$expected};

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class {$expected}FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
    }

    public function configureOptions(OptionsResolver \$resolver)
    {
        \$resolver->setDefaults([
            'translation_domain' => '{$item}',
        ]);
    }
}

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            // entity, expected, item
            ['userpassword', 'Userpassword', 'userpassword'],
            ['USERPASSWORD', 'Userpassword', 'userpassword'],
            ['UserPassword', 'UserPassword', 'userPassword'],
            ['userPassword', 'UserPassword', 'userPassword'],
            ['user_password', 'UserPassword', 'userPassword'],
            ['Posts', 'Post', 'post'],
        ];
    }
}
