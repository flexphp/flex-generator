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

use FlexPHP\Generator\Domain\Builders\FormType\FilterFormBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;

final class FilterFormBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new FilterFormBuilder(new Schema('Test', 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFilterFormType extends AbstractType
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
        $render = new FilterFormBuilder(new Schema('Test', 'Entity Foo Title', [
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

use App\Form\Type\DatefinishpickerType;
use App\Form\Type\DatestartpickerType;
use App\Form\Type\DatetimepickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('lower', InputType\TextType::class, [
            'label' => 'label.lower',
            'required' => false,
        ]);

        \$builder->add('upper', InputType\IntegerType::class, [
            'label' => 'label.upper',
            'required' => false,
        ]);

        \$builder->add('pascalCase', DatetimepickerType::class, [
            'label' => 'label.pascalCase',
            'required' => false,
        ]);

        \$builder->add('camelCase', InputType\CheckboxType::class, [
            'label' => 'label.camelCase',
            'required' => false,
        ]);

        \$builder->add('snakeCase', InputType\TextareaType::class, [
            'label' => 'label.snakeCase',
            'required' => false,
        ]);

        \$builder->add('created_START', DatestartpickerType::class, [
            'label' => 'filter.createdAtStart',
        ]);

        \$builder->add('created_END', DatefinishpickerType::class, [
            'label' => 'filter.createdAtEnd',
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
        $render = new FilterFormBuilder(new Schema('Test', 'Entity Foo Title', [
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

final class TestFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('id', InputType\TextType::class, [
            'label' => 'label.id',
            'required' => false,
        ]);

        \$builder->add('datetime', DatetimepickerType::class, [
            'label' => 'label.datetime',
            'required' => false,
        ]);

        \$builder->add('date', DatepickerType::class, [
            'label' => 'label.date',
            'required' => false,
        ]);

        \$builder->add('time', TimepickerType::class, [
            'label' => 'label.time',
            'required' => false,
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

    public function testItFileTypeOk(): void
    {
        $render = new FilterFormBuilder(new Schema('Test', 'Entity Foo Title', [
            new SchemaAttribute('id', 'string', 'pk|minlength:20|maxlength:100|required'),
            new SchemaAttribute('file', 'string', 'type:file'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('id', InputType\TextType::class, [
            'label' => 'label.id',
            'required' => false,
        ]);

        \$builder->add('file', InputType\FileType::class, [
            'label' => 'label.file',
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
        $render = new FilterFormBuilder($this->getSchemaFkRelation('PostComments'));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\PostComment;

use App\Form\Type\Select2Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PostCommentFilterFormType extends AbstractType
{
    private UrlGeneratorInterface \$router;

    public function __construct(UrlGeneratorInterface \$router)
    {
        \$this->router = \$router;
    }

    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('pk', InputType\IntegerType::class, [
            'label' => 'label.pk',
            'required' => false,
        ]);

        \$builder->add('foo', Select2Type::class, [
            'label' => 'label.foo',
            'required' => false,
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
    }

    public function testItAiAndBlameAt(): void
    {
        $render = new FilterFormBuilder($this->getSchemaAiAndBlameAt());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use App\Form\Type\DatefinishpickerType;
use App\Form\Type\DatestartpickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('key', InputType\IntegerType::class, [
            'label' => 'label.key',
            'required' => false,
        ]);

        \$builder->add('value', InputType\IntegerType::class, [
            'label' => 'label.value',
            'required' => false,
        ]);

        \$builder->add('created_START', DatestartpickerType::class, [
            'label' => 'filter.createdAtStart',
        ]);

        \$builder->add('created_END', DatefinishpickerType::class, [
            'label' => 'filter.createdAtEnd',
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
        $render = new FilterFormBuilder($this->getSchemaStringAndBlameBy());

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TestFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('code', InputType\TextType::class, [
            'label' => 'label.code',
            'required' => false,
        ]);

        \$builder->add('name', InputType\TextareaType::class, [
            'label' => 'label.name',
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

    public function testNotAttributtes(): void
    {
        $render = new FilterFormBuilder(new Schema('Test', 'Title', [
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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TestFilterFormType extends AbstractType
{
    private UrlGeneratorInterface \$router;

    public function __construct(UrlGeneratorInterface \$router)
    {
        \$this->router = \$router;
    }

    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
        \$builder->add('pk', InputType\TextType::class, [
            'label' => 'label.pk',
            'required' => false,
        ]);

        \$builder->add('required', InputType\TextType::class, [
            'label' => 'label.required',
            'required' => false,
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
        $render = new FilterFormBuilder(new Schema('Test', 'Title', [
            new SchemaAttribute('rel1', 'string', 'fk:table,name,id'),
            new SchemaAttribute('rel2', 'string', 'fk:table,name,id'),
        ]));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\Test;

use App\Form\Type\Select2Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TestFilterFormType extends AbstractType
{
    private UrlGeneratorInterface \$router;

    public function __construct(UrlGeneratorInterface \$router)
    {
        \$this->router = \$router;
    }

    public function buildForm(FormBuilderInterface \$builder, array \$options): void
    {
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
        $render = new FilterFormBuilder(new Schema($entity, 'bar', []));

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace Domain\\{$expected};

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class {$expected}FilterFormType extends AbstractType
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
