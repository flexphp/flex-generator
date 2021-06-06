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
use FlexPHP\Schema\SchemaAttribute;

final class FormTypeBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new FormTypeBuilder(new Schema('Test', 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
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

    public function configureOptions(OptionsResolver \$resolver): void
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
        $render = new FormTypeBuilder(new Schema('Test', 'Entity Foo Title', [
            new SchemaAttribute('lower', 'string', 'pk|minlength:20|maxlength:100|required'),
            new SchemaAttribute('UPPER', 'integer', 'min:2|max:10'),
            new SchemaAttribute('PascalCase', 'datetime', 'required'),
            new SchemaAttribute('camelCase', 'boolean', ''),
            new SchemaAttribute('snake_case', 'text', 'length:100,200'),
            new SchemaAttribute('created', 'datetime', 'ca'),
            new SchemaAttribute('creator', 'integer', 'cb'),
            new SchemaAttribute('updated', 'datetime', 'ua'),
            new SchemaAttribute('updater', 'integer', 'ub'),
            new SchemaAttribute('passphrase', 'string', 'required|type:password'),
            new SchemaAttribute('zone', 'string', 'type:timezone'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use App\Form\Type\DatetimepickerType;
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
        \$builder->add('pascalCase', DatetimepickerType::class, [
            'label' => 'label.pascalCase',
            'required' => true,
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
        \$builder->add('passphrase', InputType\PasswordType::class, [
            'label' => 'label.passphrase',
            'required' => true,
        ]);
        \$builder->add('zone', InputType\TimezoneType::class, [
            'label' => 'label.zone',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver \$resolver): void
    {
        \$resolver->setDefaults([
            'translation_domain' => 'test',
        ]);
    }
}

T
, $render->build());
    }

    public function testItOkWithDateOrTimeProperties(): void
    {
        $render = new FormTypeBuilder(new Schema('Test', 'Entity Foo Title', [
            new SchemaAttribute('id', 'string', 'pk|minlength:20|maxlength:100|required'),
            new SchemaAttribute('datetime', 'datetime', 'required'),
            new SchemaAttribute('date', 'date', 'required'),
            new SchemaAttribute('time', 'time', 'required'),
            new SchemaAttribute('datetimeOptional', 'datetime'),
            new SchemaAttribute('dateOptional', 'date'),
            new SchemaAttribute('timeOptional', 'time'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use App\Form\Type\DatepickerType;
use App\Form\Type\DatetimepickerType;
use App\Form\Type\TimepickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('id', InputType\TextType::class, [
            'label' => 'label.id',
            'required' => true,
            'attr' => [
                'minlength' => 20,
                'maxlength' => 100,
            ],
        ]);
        \$builder->add('datetime', DatetimepickerType::class, [
            'label' => 'label.datetime',
            'required' => true,
        ]);
        \$builder->add('date', DatepickerType::class, [
            'label' => 'label.date',
            'required' => true,
        ]);
        \$builder->add('time', TimepickerType::class, [
            'label' => 'label.time',
            'required' => true,
        ]);
        \$builder->add('datetimeOptional', DatetimepickerType::class, [
            'label' => 'label.datetimeOptional',
            'required' => false,
        ]);
        \$builder->add('dateOptional', DatepickerType::class, [
            'label' => 'label.dateOptional',
            'required' => false,
        ]);
        \$builder->add('timeOptional', TimepickerType::class, [
            'label' => 'label.timeOptional',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver \$resolver): void
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

        // @codingStandardsIgnoreStart
        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\PostComment;

use App\Form\Type\Select2Type;
use Domain\Bar\Request\ReadBarRequest;
use Domain\Bar\UseCase\ReadBarUseCase;
use Domain\Post\Request\ReadPostRequest;
use Domain\Post\UseCase\ReadPostUseCase;
use Domain\UserStatus\Request\ReadUserStatusRequest;
use Domain\UserStatus\UseCase\ReadUserStatusUseCase;
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
    private ReadBarUseCase \$readBarUseCase;

    private ReadPostUseCase \$readPostUseCase;

    private ReadUserStatusUseCase \$readUserStatusUseCase;

    private UrlGeneratorInterface \$router;

    public function __construct(
        ReadBarUseCase \$readBarUseCase,
        ReadPostUseCase \$readPostUseCase,
        ReadUserStatusUseCase \$readUserStatusUseCase,
        UrlGeneratorInterface \$router
    ) {
        \$this->readBarUseCase = \$readBarUseCase;
        \$this->readPostUseCase = \$readPostUseCase;
        \$this->readUserStatusUseCase = \$readUserStatusUseCase;
        \$this->router = \$router;
    }

    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$fooModifier = function (FormInterface \$form, ?string \$value): void {
            \$choices = null;

            if (!empty(\$value)) {
                \$response = \$this->readBarUseCase->execute(new ReadBarRequest(\$value));

                if (\$response->bar->baz()) {
                    \$choices = [\$response->bar->fuz() => \$value];
                }
            }

            \$form->add('foo', Select2Type::class, [
                'label' => 'label.foo',
                'required' => true,
                'attr' => [
                    'data-autocomplete-url' => \$this->router->generate('post-comments.find.bars'),
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

        \$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent \$event) use (\$fooModifier): void {
            \$fooModifier(\$event->getForm(), (string)\$event->getData()['foo'] ?: null);
        });

        \$postIdModifier = function (FormInterface \$form, ?int \$value): void {
            \$choices = null;

            if (!empty(\$value)) {
                \$response = \$this->readPostUseCase->execute(new ReadPostRequest(\$value));

                if (\$response->post->id()) {
                    \$choices = [\$response->post->name() => \$value];
                }
            }

            \$form->add('postId', Select2Type::class, [
                'label' => 'label.postId',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => \$this->router->generate('post-comments.find.posts'),
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

        \$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent \$event) use (\$postIdModifier): void {
            \$postIdModifier(\$event->getForm(), (int)\$event->getData()['postId'] ?: null);
        });

        \$statusIdModifier = function (FormInterface \$form, ?int \$value): void {
            \$choices = null;

            if (!empty(\$value)) {
                \$response = \$this->readUserStatusUseCase->execute(new ReadUserStatusRequest(\$value));

                if (\$response->userStatus->id()) {
                    \$choices = [\$response->userStatus->name() => \$value];
                }
            }

            \$form->add('statusId', Select2Type::class, [
                'label' => 'label.statusId',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => \$this->router->generate('post-comments.find.user-status'),
                ],
                'choices' => \$choices,
                'data' => \$value,
            ]);
        };

        \$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent \$event) use (\$statusIdModifier) {
            if (!\$event->getData()) {
                return null;
            }

            \$statusIdModifier(\$event->getForm(), \$event->getData()->statusId());
        });

        \$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent \$event) use (\$statusIdModifier): void {
            \$statusIdModifier(\$event->getForm(), (int)\$event->getData()['statusId'] ?: null);
        });

        \$builder->add('foo', Select2Type::class, [
            'label' => 'label.foo',
            'required' => true,
            'attr' => [
                'data-autocomplete-url' => \$this->router->generate('post-comments.find.bars'),
            ],
        ]);
        \$builder->add('postId', Select2Type::class, [
            'label' => 'label.postId',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => \$this->router->generate('post-comments.find.posts'),
            ],
        ]);
        \$builder->add('statusId', Select2Type::class, [
            'label' => 'label.statusId',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => \$this->router->generate('post-comments.find.user-status'),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver \$resolver): void
    {
        \$resolver->setDefaults([
            'translation_domain' => 'postComment',
        ]);
    }
}

T
, $render->build());
        // @codingStandardsIgnoreEnd
    }

    public function testItAiAndBlameAt(): void
    {
        $render = new FormTypeBuilder($this->getSchemaAiAndBlameAt());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
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

    public function configureOptions(OptionsResolver \$resolver): void
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
{$this->header}
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

    public function configureOptions(OptionsResolver \$resolver): void
    {
        \$resolver->setDefaults([
            'translation_domain' => 'test',
        ]);
    }
}

T
, $render->build());
    }

    public function testNotAttributtes(): void
    {
        $render = new FormTypeBuilder(new Schema('Test', 'Title', [
            new SchemaAttribute('pk', 'string', 'pk|required'),
            new SchemaAttribute('required', 'string', 'required'),
            new SchemaAttribute('type', 'string', 'type:number'),
            new SchemaAttribute('filter', 'string', 'filter:ss'),
            new SchemaAttribute('format', 'integer', 'format:money'),
            new SchemaAttribute('trim', 'string', 'trim'),
            new SchemaAttribute('fchars', 'string', 'fk:table,name,id|fchars:1'),
            new SchemaAttribute('fkcheck', 'string', 'fk:table,name,id|fkcheck'),
            new SchemaAttribute('link', 'string', 'link'),
            new SchemaAttribute('show', 'string', 'show:a'),
            new SchemaAttribute('hide', 'string', 'hide:a'),
            new SchemaAttribute('default', 'string', 'default:A'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use App\Form\Type\Select2Type;
use Domain\Table\Request\ReadTableRequest;
use Domain\Table\UseCase\ReadTableUseCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TestFormType extends AbstractType
{
    private ReadTableUseCase \$readTableUseCase;

    private UrlGeneratorInterface \$router;

    public function __construct(
        ReadTableUseCase \$readTableUseCase,
        UrlGeneratorInterface \$router
    ) {
        \$this->readTableUseCase = \$readTableUseCase;
        \$this->router = \$router;
    }

    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$fcharsModifier = function (FormInterface \$form, ?string \$value): void {
            \$choices = null;

            if (!empty(\$value)) {
                \$response = \$this->readTableUseCase->execute(new ReadTableRequest(\$value));

                if (\$response->table->id()) {
                    \$choices = [\$response->table->name() => \$value];
                }
            }

            \$form->add('fchars', Select2Type::class, [
                'label' => 'label.fchars',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => \$this->router->generate('tests.find.tables'),
                ],
                'choices' => \$choices,
                'data' => \$value,
            ]);
        };

        \$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent \$event) use (\$fcharsModifier) {
            if (!\$event->getData()) {
                return null;
            }

            \$fcharsModifier(\$event->getForm(), \$event->getData()->fchars());
        });

        \$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent \$event) use (\$fcharsModifier): void {
            \$fcharsModifier(\$event->getForm(), (string)\$event->getData()['fchars'] ?: null);
        });

        \$fkcheckModifier = function (FormInterface \$form, ?string \$value): void {
            \$choices = null;

            if (!empty(\$value)) {
                \$response = \$this->readTableUseCase->execute(new ReadTableRequest(\$value));

                if (\$response->table->id()) {
                    \$choices = [\$response->table->name() => \$value];
                }
            }

            \$form->add('fkcheck', Select2Type::class, [
                'label' => 'label.fkcheck',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => \$this->router->generate('tests.find.tables'),
                ],
                'choices' => \$choices,
                'data' => \$value,
            ]);
        };

        \$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent \$event) use (\$fkcheckModifier) {
            if (!\$event->getData()) {
                return null;
            }

            \$fkcheckModifier(\$event->getForm(), \$event->getData()->fkcheck());
        });

        \$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent \$event) use (\$fkcheckModifier): void {
            \$fkcheckModifier(\$event->getForm(), (string)\$event->getData()['fkcheck'] ?: null);
        });

        \$builder->add('pk', InputType\TextType::class, [
            'label' => 'label.pk',
            'required' => true,
        ]);
        \$builder->add('required', InputType\TextType::class, [
            'label' => 'label.required',
            'required' => true,
        ]);
        \$builder->add('type', InputType\TextType::class, [
            'label' => 'label.type',
            'required' => false,
        ]);
        \$builder->add('filter', InputType\TextType::class, [
            'label' => 'label.filter',
            'required' => false,
        ]);
        \$builder->add('format', InputType\IntegerType::class, [
            'label' => 'label.format',
            'required' => false,
        ]);
        \$builder->add('trim', InputType\TextType::class, [
            'label' => 'label.trim',
            'required' => false,
        ]);
        \$builder->add('fchars', Select2Type::class, [
            'label' => 'label.fchars',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => \$this->router->generate('tests.find.tables'),
            ],
        ]);
        \$builder->add('fkcheck', Select2Type::class, [
            'label' => 'label.fkcheck',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => \$this->router->generate('tests.find.tables'),
            ],
        ]);
        \$builder->add('link', InputType\TextType::class, [
            'label' => 'label.link',
            'required' => false,
        ]);
        \$builder->add('show', InputType\TextType::class, [
            'label' => 'label.show',
            'required' => false,
        ]);
        \$builder->add('hide', InputType\TextType::class, [
            'label' => 'label.hide',
            'required' => false,
        ]);
        \$builder->add('default', InputType\TextType::class, [
            'label' => 'label.default',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver \$resolver): void
    {
        \$resolver->setDefaults([
            'translation_domain' => 'test',
        ]);
    }
}

T
, $render->build());
    }

    public function testSameFkUsedInTwoAttributes(): void
    {
        $render = new FormTypeBuilder(new Schema('Test', 'Title', [
            new SchemaAttribute('rel1', 'string', 'fk:table,name,id'),
            new SchemaAttribute('rel2', 'string', 'fk:table,name,id'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use App\Form\Type\Select2Type;
use Domain\Table\Request\ReadTableRequest;
use Domain\Table\UseCase\ReadTableUseCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TestFormType extends AbstractType
{
    private ReadTableUseCase \$readTableUseCase;

    private UrlGeneratorInterface \$router;

    public function __construct(
        ReadTableUseCase \$readTableUseCase,
        UrlGeneratorInterface \$router
    ) {
        \$this->readTableUseCase = \$readTableUseCase;
        \$this->router = \$router;
    }

    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$rel1Modifier = function (FormInterface \$form, ?string \$value): void {
            \$choices = null;

            if (!empty(\$value)) {
                \$response = \$this->readTableUseCase->execute(new ReadTableRequest(\$value));

                if (\$response->table->id()) {
                    \$choices = [\$response->table->name() => \$value];
                }
            }

            \$form->add('rel1', Select2Type::class, [
                'label' => 'label.rel1',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => \$this->router->generate('tests.find.tables'),
                ],
                'choices' => \$choices,
                'data' => \$value,
            ]);
        };

        \$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent \$event) use (\$rel1Modifier) {
            if (!\$event->getData()) {
                return null;
            }

            \$rel1Modifier(\$event->getForm(), \$event->getData()->rel1());
        });

        \$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent \$event) use (\$rel1Modifier): void {
            \$rel1Modifier(\$event->getForm(), (string)\$event->getData()['rel1'] ?: null);
        });

        \$rel2Modifier = function (FormInterface \$form, ?string \$value): void {
            \$choices = null;

            if (!empty(\$value)) {
                \$response = \$this->readTableUseCase->execute(new ReadTableRequest(\$value));

                if (\$response->table->id()) {
                    \$choices = [\$response->table->name() => \$value];
                }
            }

            \$form->add('rel2', Select2Type::class, [
                'label' => 'label.rel2',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => \$this->router->generate('tests.find.tables'),
                ],
                'choices' => \$choices,
                'data' => \$value,
            ]);
        };

        \$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent \$event) use (\$rel2Modifier) {
            if (!\$event->getData()) {
                return null;
            }

            \$rel2Modifier(\$event->getForm(), \$event->getData()->rel2());
        });

        \$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent \$event) use (\$rel2Modifier): void {
            \$rel2Modifier(\$event->getForm(), (string)\$event->getData()['rel2'] ?: null);
        });

        \$builder->add('rel1', Select2Type::class, [
            'label' => 'label.rel1',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => \$this->router->generate('tests.find.tables'),
            ],
        ]);
        \$builder->add('rel2', Select2Type::class, [
            'label' => 'label.rel2',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => \$this->router->generate('tests.find.tables'),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver \$resolver): void
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
{$this->header}
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

    public function configureOptions(OptionsResolver \$resolver): void
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
