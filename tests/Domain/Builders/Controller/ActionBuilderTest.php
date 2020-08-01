<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Controller;

use FlexPHP\Generator\Domain\Builders\Controller\ActionBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;

final class ActionBuilderTest extends TestCase
{
    /**
     * @dataProvider getEntityAndRouteName
     */
    public function testItRenderOk(string $entity, string $route, string $role): void
    {
        $render = new ActionBuilder(new Schema($entity, 'bar', []), '');

        $this->assertEquals(<<<T
    /**
     * @Route("/", methods={"GET"}, name="{$route}.index")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_{$role}_INDEX')", statusCode=401)
     */
    public function index(Request \$request, Connection \$conn): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderIndexOk(): void
    {
        $render = new ActionBuilder(new Schema('Test', 'bar', []), 'index');

        $this->assertEquals(<<<T
    /**
     * @Route("/", methods={"GET"}, name="tests.index")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_INDEX')", statusCode=401)
     */
    public function index(Request \$request, Connection \$conn): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new ActionBuilder(new Schema('Test', 'bar', []), 'create');

        $this->assertEquals(<<<T
    /**
     * @Route("/new", methods={"GET"}, name="tests.new")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        \$form = \$this->createForm(TestFormType::class);

        return \$this->render('test/new.html.twig', [
            'form' => \$form->createView(),
        ]);
    }

    /**
     * @Route("/create", methods={"POST"}, name="tests.create")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_CREATE')", statusCode=401)
     */
    public function create(Request \$request, Connection \$conn): Response
    {
    }

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItRenderCreateDiffNameOk(
        string $name,
        string $expected,
        string $route,
        string $template,
        string $role
    ): void {
        $render = new ActionBuilder(new Schema($name, 'bar', []), 'create');

        $this->assertEquals(<<<T
    /**
     * @Route("/new", methods={"GET"}, name="{$route}.new")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_{$role}_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        \$form = \$this->createForm({$expected}FormType::class);

        return \$this->render('{$template}/new.html.twig', [
            'form' => \$form->createView(),
        ]);
    }

    /**
     * @Route("/create", methods={"POST"}, name="{$route}.create")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_{$role}_CREATE')", statusCode=401)
     */
    public function create(Request \$request, Connection \$conn): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new ActionBuilder(new Schema('Test', 'bar', []), 'read');

        $this->assertEquals(<<<T
    /**
     * @Route("/{id}", methods={"GET"}, name="tests.read")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_READ')", statusCode=401)
     */
    public function read(Connection \$conn, string \$id): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new ActionBuilder(new Schema('Test', 'bar', []), 'update');

        $this->assertEquals(<<<T
    /**
     * @Route("/edit/{id}", methods={"GET"}, name="tests.edit")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_UPDATE')", statusCode=401)
     */
    public function edit(Connection \$conn, string \$id): Response
    {
        \$request = new ReadTestRequest(\$id);

        \$useCase = new ReadTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        if (!\$response->test->id()) {
            throw \$this->createNotFoundException();
        }

        \$form = \$this->createForm(TestFormType::class, \$response->test);

        return \$this->render('test/edit.html.twig', [
            'register' => \$response->test,
            'form' => \$form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", methods={"PUT"}, name="tests.update")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_UPDATE')", statusCode=401)
     */
    public function update(Request \$request, Connection \$conn, string \$id): Response
    {
    }

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItRenderUpdateDiffNameOk(
        string $name,
        string $expected,
        string $route,
        string $template,
        string $role,
        string $item
    ): void {
        $render = new ActionBuilder(new Schema($name, 'bar', []), 'update');

        $this->assertEquals(<<<T
    /**
     * @Route("/edit/{id}", methods={"GET"}, name="{$route}.edit")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_{$role}_UPDATE')", statusCode=401)
     */
    public function edit(Connection \$conn, string \$id): Response
    {
        \$request = new Read{$expected}Request(\$id);

        \$useCase = new Read{$expected}UseCase(new {$expected}Repository(new MySQL{$expected}Gateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        if (!\$response->{$item}->id()) {
            throw \$this->createNotFoundException();
        }

        \$form = \$this->createForm({$expected}FormType::class, \$response->{$item});

        return \$this->render('{$template}/edit.html.twig', [
            'register' => \$response->{$item},
            'form' => \$form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", methods={"PUT"}, name="{$route}.update")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_{$role}_UPDATE')", statusCode=401)
     */
    public function update(Request \$request, Connection \$conn, string \$id): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new ActionBuilder(new Schema('Test', 'bar', [
            new SchemaAttribute('foo', 'integer', 'pk|ai|required'),
        ]), 'delete');

        $this->assertEquals(<<<T
    /**
     * @Route("/delete/{id}", methods={"DELETE"}, name="tests.delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_DELETE')", statusCode=401)
     */
    public function delete(Connection \$conn, int \$id): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderUpdateAutoIncrementalAndBlameableOk(): void
    {
        $render = new ActionBuilder($this->getSchemaAiAndBlame(), 'update');

        $this->assertEquals(<<<T
    /**
     * @Route("/edit/{id}", methods={"GET"}, name="tests.edit")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_UPDATE')", statusCode=401)
     */
    public function edit(Connection \$conn, int \$id): Response
    {
        \$request = new ReadTestRequest(\$id);

        \$useCase = new ReadTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        if (!\$response->test->key()) {
            throw \$this->createNotFoundException();
        }

        \$form = \$this->createForm(TestFormType::class, \$response->test);

        return \$this->render('test/edit.html.twig', [
            'register' => \$response->test,
            'form' => \$form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", methods={"PUT"}, name="tests.update")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_UPDATE')", statusCode=401)
     */
    public function update(Request \$request, Connection \$conn, int \$id): Response
    {
    }

T
, $render->build());
    }

    /**
     * @dataProvider getCustomActions
     *
     * @param string $action
     */
    public function testItRenderCustomActionOk($action): void
    {
        $render = new ActionBuilder(new Schema('FooBar', 'bar', []), $action);

        $this->assertEquals(<<<T
    /**
     * @Route("/custom-action", methods={"POST"}, name="foo-bars.custom-action")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_FOOBAR_CUSTOMACTION')", statusCode=401)
     */
    public function customAction(Request \$request): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderWithRequestMessage(): void
    {
        $requestMessage = '// foo';

        $render = new ActionBuilder(new Schema('Test', 'bar', []), 'action', $requestMessage);

        $this->assertEquals(<<<T
    /**
     * @Route("/action", methods={"POST"}, name="tests.action")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_ACTION')", statusCode=401)
     */
    public function action(Request \$request): Response
    {
        $requestMessage
    }

T
, $render->build());
    }

    public function testItRenderWithUseCase(): void
    {
        $useCase = '// bar';

        $render = new ActionBuilder(new Schema('Test', 'bar', []), 'action', $useCase);

        $this->assertEquals(<<<T
    /**
     * @Route("/action", methods={"POST"}, name="tests.action")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_ACTION')", statusCode=401)
     */
    public function action(Request \$request): Response
    {
        $useCase
    }

T
, $render->build());
    }

    public function testItRenderWithResponseMessage(): void
    {
        $responseMessage = '// buz';

        $render = new ActionBuilder(new Schema('Test', 'bar', []), 'action', $responseMessage);

        $this->assertEquals(<<<T
    /**
     * @Route("/action", methods={"POST"}, name="tests.action")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_ACTION')", statusCode=401)
     */
    public function action(Request \$request): Response
    {
        $responseMessage
    }

T
, $render->build());
    }

    public function testItRenderComplete(): void
    {
        $requestMessage = '// ' . __LINE__;
        $useCase = '// ' . __LINE__;
        $responseMessage = '// ' . __LINE__;

        $render = new ActionBuilder(
            new Schema('Test', 'bar', []),
            'action',
            $requestMessage,
            $useCase,
            $responseMessage
        );

        $this->assertEquals(<<<T
    /**
     * @Route("/action", methods={"POST"}, name="tests.action")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_ACTION')", statusCode=401)
     */
    public function action(Request \$request): Response
    {
        $requestMessage

        $useCase

        $responseMessage
    }

T
, $render->build());
    }

    public function testItRenderToString(): void
    {
        $render = new ActionBuilder(new Schema('Test', 'bar', []), 'action');

        $this->assertEquals(<<<T
    /**
     * @Route("/action", methods={"POST"}, name="tests.action")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_ACTION')", statusCode=401)
     */
    public function action(Request \$request): Response
    {
    }

T
, $render);
    }

    public function getCustomActions(): array
    {
        return [
            ['custom_action'],
            ['custom action'],
            ['Custom Action'],
            ['cUSTOM aCtion'],
            ['customAction'],
            ['CustomAction'],
            ['custom-action'],
        ];
    }

    public function getEntityAndRouteName(): array
    {
        return [
            // entity, route, role
            ['userpassword', 'userpasswords', 'USERPASSWORD'],
            ['USERPASSWORD', 'userpasswords', 'USERPASSWORD'],
            ['UserPassword', 'user-passwords', 'USERPASSWORD'],
            ['userPassword', 'user-passwords', 'USERPASSWORD'],
            ['user_password', 'user-passwords', 'USERPASSWORD'],
            ['Posts', 'posts', 'POST'],
        ];
    }

    public function getEntityName(): array
    {
        return [
            // entity, function, route, template, role, item
            ['userpassword', 'Userpassword', 'userpasswords', 'userpassword', 'USERPASSWORD', 'userpassword'],
            ['USERPASSWORD', 'Userpassword', 'userpasswords', 'userpassword', 'USERPASSWORD', 'userpassword'],
            ['UserPassword', 'UserPassword', 'user-passwords', 'userPassword', 'USERPASSWORD', 'userPassword'],
            ['userPassword', 'UserPassword', 'user-passwords', 'userPassword', 'USERPASSWORD', 'userPassword'],
            ['user_password', 'UserPassword', 'user-passwords', 'userPassword', 'USERPASSWORD', 'userPassword'],
            ['Posts', 'Post', 'posts', 'post', 'POST', 'post'],
        ];
    }
}
