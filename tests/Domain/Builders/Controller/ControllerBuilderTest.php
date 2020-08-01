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
use FlexPHP\Generator\Domain\Builders\Controller\ControllerBuilder;
use FlexPHP\Generator\Domain\Builders\Controller\RequestMessageBuilder;
use FlexPHP\Generator\Domain\Builders\Controller\ResponseMessageBuilder;
use FlexPHP\Generator\Domain\Builders\Controller\UseCaseBuilder;
use FlexPHP\Generator\Tests\TestCase;
use FlexPHP\Schema\Schema;
use FlexPHP\Schema\SchemaAttribute;

final class ControllerBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $render = new ControllerBuilder(new Schema('Test', 'bar', []), []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
}

T
, $render->build());
    }

    /**
     * @dataProvider getEntityAndRouteName
     */
    public function testItRenderOkWithDiffNameEntity(string $entity, string $expectedName, string $expectedRoute): void
    {
        $render = new ControllerBuilder(new Schema($entity, 'bar', []), []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{$expectedRoute}")
 */
final class {$expectedName}Controller extends AbstractController
{
}

T
, $render->build());
    }

    public function testItRenderIndexOk(): void
    {
        $schema = new Schema('Test', 'bar', []);
        $action = 'index';
        $actions = [
            $action => (new ActionBuilder(
                $schema,
                $action,
                (new RequestMessageBuilder($schema, $action))->build(),
                (new UseCaseBuilder($schema, $action))->build(),
                (new ResponseMessageBuilder($schema, $action))->build()
            ))->build(),
        ];

        $render = new ControllerBuilder($schema, $actions);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Domain\Test\TestFactory;
use Domain\Test\TestFormType;
use Domain\Test\TestRepository;
use Domain\Test\Gateway\MySQLTestGateway;
use Domain\Test\Request\IndexTestRequest;
use Domain\Test\UseCase\IndexTestUseCase;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="tests.index")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_INDEX')", statusCode=401)
     */
    public function index(Request \$request, Connection \$conn): Response
    {
        \$request = new IndexTestRequest(\$request->request->all());

        \$useCase = new IndexTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        return \$this->render('test/index.html.twig', [
            'registers' => \$response->tests,
        ]);
    }
}

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $schema = new Schema('Test', 'bar', []);
        $action = 'create';
        $actions = [
            $action => (new ActionBuilder(
                $schema,
                $action,
                (new RequestMessageBuilder($schema, $action))->build(),
                (new UseCaseBuilder($schema, $action))->build(),
                (new ResponseMessageBuilder($schema, $action))->build()
            ))->build(),
        ];

        $render = new ControllerBuilder($schema, $actions);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Domain\Test\TestFactory;
use Domain\Test\TestFormType;
use Domain\Test\TestRepository;
use Domain\Test\Gateway\MySQLTestGateway;
use Domain\Test\Request\CreateTestRequest;
use Domain\Test\UseCase\CreateTestUseCase;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
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
        \$form = \$this->createForm(TestFormType::class);
        \$form->handleRequest(\$request);

        \$request = new CreateTestRequest(\$form->getData());

        \$useCase = new CreateTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        \$this->addFlash(\$response->status, \$response->message);

        return \$this->redirectToRoute('tests.index');
    }
}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $schema = new Schema('Test', 'bar', []);
        $action = 'read';
        $actions = [
            $action => (new ActionBuilder(
                $schema,
                $action,
                (new RequestMessageBuilder($schema, $action))->build(),
                (new UseCaseBuilder($schema, $action))->build(),
                (new ResponseMessageBuilder($schema, $action))->build()
            ))->build(),
        ];

        $render = new ControllerBuilder($schema, $actions);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Domain\Test\TestFactory;
use Domain\Test\TestFormType;
use Domain\Test\TestRepository;
use Domain\Test\Gateway\MySQLTestGateway;
use Domain\Test\Request\ReadTestRequest;
use Domain\Test\UseCase\ReadTestUseCase;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
    /**
     * @Route("/{id}", methods={"GET"}, name="tests.read")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_READ')", statusCode=401)
     */
    public function read(Connection \$conn, string \$id): Response
    {
        \$request = new ReadTestRequest(\$id);

        \$useCase = new ReadTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        if (!\$response->test->id()) {
            throw \$this->createNotFoundException();
        }

        return \$this->render('test/show.html.twig', [
            'register' => \$response->test,
        ]);
    }
}

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $schema = new Schema('Test', 'bar', [new SchemaAttribute('Foo', 'integer', 'pk|ai|required')]);
        $action = 'update';
        $actions = [
            $action => (new ActionBuilder(
                $schema,
                $action,
                (new RequestMessageBuilder($schema, $action))->build(),
                (new UseCaseBuilder($schema, $action))->build(),
                (new ResponseMessageBuilder($schema, $action))->build()
            ))->build(),
        ];

        $render = new ControllerBuilder($schema, $actions);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Domain\Test\TestFactory;
use Domain\Test\TestFormType;
use Domain\Test\TestRepository;
use Domain\Test\Gateway\MySQLTestGateway;
use Domain\Test\Request\UpdateTestRequest;
use Domain\Test\UseCase\UpdateTestUseCase;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
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

        if (!\$response->test->foo()) {
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
        \$form = \$this->createForm(TestFormType::class);
        \$form->submit(\$request->request->get(\$form->getName()));
        \$form->handleRequest(\$request);

        \$request = new UpdateTestRequest(\$id, \$form->getData());

        \$useCase = new UpdateTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        \$this->addFlash(\$response->status, \$response->message);

        return \$this->redirectToRoute('tests.index');
    }
}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $schema = new Schema('Test', 'bar', [new SchemaAttribute('foo', 'integer', 'pk|ai|required')]);
        $action = 'delete';
        $actions = [
            $action => (new ActionBuilder(
                $schema,
                $action,
                (new RequestMessageBuilder($schema, $action))->build(),
                (new UseCaseBuilder($schema, $action))->build(),
                (new ResponseMessageBuilder($schema, $action))->build()
            ))->build(),
        ];

        $render = new ControllerBuilder($schema, $actions);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Domain\Test\TestFactory;
use Domain\Test\TestFormType;
use Domain\Test\TestRepository;
use Domain\Test\Gateway\MySQLTestGateway;
use Domain\Test\Request\DeleteTestRequest;
use Domain\Test\UseCase\DeleteTestUseCase;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
    /**
     * @Route("/delete/{id}", methods={"DELETE"}, name="tests.delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_DELETE')", statusCode=401)
     */
    public function delete(Connection \$conn, int \$id): Response
    {
        \$request = new DeleteTestRequest(\$id);

        \$useCase = new DeleteTestUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        \$this->addFlash(\$response->status, \$response->message);

        return \$this->redirectToRoute('tests.index');
    }
}

T
, $render->build());
    }

    public function testItRenderLoginOk(): void
    {
        $schema = new Schema('Test', 'bar', []);
        $action = 'login';
        $actions = [
            $action => (new ActionBuilder(
                $schema,
                $action,
                (new RequestMessageBuilder($schema, $action))->build(),
                (new UseCaseBuilder($schema, $action))->build(),
                (new ResponseMessageBuilder($schema, $action))->build()
            ))->build(),
        ];

        $render = new ControllerBuilder($schema, $actions);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
}

T
, $render->build());
    }

    public function testItRenderFkRelationsOk(): void
    {
        $render = new ControllerBuilder($this->getSchemaFkRelation(), []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Domain\Test\Request\FindTestBarRequest;
use Domain\Test\UseCase\FindTestBarUseCase;
use Domain\Test\Request\FindTestPostRequest;
use Domain\Test\UseCase\FindTestPostUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
    /**
     * @Route("/find-bars", methods={"POST"}, name="tests.find.bars")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BAR_INDEX')", statusCode=401)
     */
    public function findBar(Request \$request, Connection \$conn): Response
    {
        if (!\$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        \$request = new FindTestBarRequest(\$request->request->all());

        \$useCase = new FindTestBarUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        return new JsonResponse([
            'results' => \$response->bars,
            'pagination' => ['more' => false],
        ]);
    }

    /**
     * @Route("/find-posts", methods={"POST"}, name="tests.find.posts")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_POST_INDEX')", statusCode=401)
     */
    public function findPost(Request \$request, Connection \$conn): Response
    {
        if (!\$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        \$request = new FindTestPostRequest(\$request->request->all());

        \$useCase = new FindTestPostUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        return new JsonResponse([
            'results' => \$response->posts,
            'pagination' => ['more' => false],
        ]);
    }
}

T
, $render->build());
    }

    public function testItRenderBlameByOk(): void
    {
        $render = new ControllerBuilder($this->getSchemaStringAndBlameBy(), []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Domain\Test\Request\FindTestUserRequest;
use Domain\Test\UseCase\FindTestUserUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
    /**
     * @Route("/find-users", methods={"POST"}, name="tests.find.users")
     * @Cache(smaxage="10")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USER_INDEX')", statusCode=401)
     */
    public function findUser(Request \$request, Connection \$conn): Response
    {
        if (!\$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        \$request = new FindTestUserRequest(\$request->request->all());

        \$useCase = new FindTestUserUseCase(new TestRepository(new MySQLTestGateway(\$conn)));

        \$response = \$useCase->execute(\$request);

        return new JsonResponse([
            'results' => \$response->users,
            'pagination' => ['more' => false],
        ]);
    }
}

T
, $render->build());
    }

    public function testItRenderMixedOk(): void
    {
        $schema = new Schema('Test', 'bar', []);
        $actions = [
            'action' => (new ActionBuilder(
                $schema,
                'action',
                (new RequestMessageBuilder($schema, 'action'))->build(),
                (new UseCaseBuilder($schema, 'action'))->build(),
                (new ResponseMessageBuilder($schema, 'action'))->build()
            ))->build(),
            'custom Fuz' => (new ActionBuilder(
                $schema,
                'custom Fuz',
                (new RequestMessageBuilder($schema, 'custom Fuz'))->build(),
                (new UseCaseBuilder($schema, 'custom Fuz'))->build(),
                (new ResponseMessageBuilder($schema, 'custom Fuz'))->build()
            ))->build(),
        ];

        $render = new ControllerBuilder($schema, $actions);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Domain\Test\Request\ActionTestRequest;
use Domain\Test\Request\CustomFuzTestRequest;
use Domain\Test\UseCase\ActionTestUseCase;
use Domain\Test\UseCase\CustomFuzTestUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
    /**
     * @Route("/action", methods={"POST"}, name="tests.action")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_ACTION')", statusCode=401)
     */
    public function action(Request \$request): Response
    {
        \$request = new ActionTestRequest(\$request->request->all());

        \$useCase = new ActionTestUseCase();
        \$response = \$useCase->execute(\$request);

        return new Response(\$response);
    }

    /**
     * @Route("/custom-fuz", methods={"POST"}, name="tests.custom-fuz")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_CUSTOMFUZ')", statusCode=401)
     */
    public function customFuz(Request \$request): Response
    {
        \$request = new CustomFuzTestRequest(\$request->request->all());

        \$useCase = new CustomFuzTestUseCase();
        \$response = \$useCase->execute(\$request);

        return new Response(\$response);
    }
}

T
, $render->build());
    }

    public function getEntityAndRouteName(): array
    {
        return [
            // entity, controller, route
            ['userpassword', 'Userpassword', 'userpasswords'],
            ['USERPASSWORD', 'Userpassword', 'userpasswords'],
            ['UserPassword', 'UserPassword', 'user-passwords'],
            ['userPassword', 'UserPassword', 'user-passwords'],
            ['user_password', 'UserPassword', 'user-passwords'],
            ['Posts', 'Post', 'posts'],
        ];
    }
}
