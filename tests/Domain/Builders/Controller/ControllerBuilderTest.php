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
{$this->header}
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
{$this->header}
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
{$this->header}
namespace App\Controller;

use Domain\Test\Request\IndexTestRequest;
use Domain\Test\TestFormType;
use Domain\Test\UseCase\IndexTestUseCase;
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
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_INDEX')", statusCode=401)
     */
    public function index(Request \$request, IndexTestUseCase \$useCase): Response
    {
        \$template = \$request->isXmlHttpRequest() ? 'test/_ajax.html.twig' : 'test/index.html.twig';

        \$request = new IndexTestRequest(\$request->request->all(), (int)\$request->query->get('page', 1));

        \$response = \$useCase->execute(\$request);

        return \$this->render(\$template, [
            'tests' => \$response->tests,
        ]);
    }
}

T
, $render->build());
    }

    public function testItRenderFilterOk(): void
    {
        $schema = new Schema('Test', 'bar', [], null, null, ['f']);
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

        // @codingStandardsIgnoreStarts
        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace App\Controller;

use Domain\Test\Request\IndexTestRequest;
use Domain\Test\TestFilterFormType;
use Domain\Test\TestFormType;
use Domain\Test\UseCase\IndexTestUseCase;
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
     * @Route("/", methods={"GET","POST"}, name="tests.index")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_INDEX')", statusCode=401)
     */
    public function index(Request \$request, IndexTestUseCase \$useCase): Response
    {
        \$filters = \$request->isMethod('POST')
            ? \$request->request->filter('test_filter_form', [])
            : \$request->query->filter('test_filter_form', []);

        \$template = \$request->isXmlHttpRequest() ? 'test/_ajax.html.twig' : 'test/index.html.twig';

        \$request = new IndexTestRequest(\$filters, (int)\$request->query->get('page', 1), 50, \$this->getUser()->timezone());

        \$response = \$useCase->execute(\$request);

        return \$this->render(\$template, [
            'tests' => \$response->tests,
            'filter' => (\$this->createForm(TestFilterFormType::class))->createView(),
        ]);
    }
}

T
, $render->build());
        // @codingStandardsIgnoreEnd
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
{$this->header}
namespace App\Controller;

use Domain\Test\Request\CreateTestRequest;
use Domain\Test\TestFormType;
use Domain\Test\UseCase\CreateTestUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/tests")
 */
final class TestController extends AbstractController
{
    /**
     * @Route("/new", methods={"GET"}, name="tests.new")
     * @Cache(smaxage="3600")
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
    public function create(Request \$request, CreateTestUseCase \$useCase, TranslatorInterface \$trans): Response
    {
        \$form = \$this->createForm(TestFormType::class);
        \$form->handleRequest(\$request);

        \$request = new CreateTestRequest(\$form->getData());

        \$useCase->execute(\$request);

        \$this->addFlash('success', \$trans->trans('message.created', [], 'test'));

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
{$this->header}
namespace App\Controller;

use Domain\Test\Request\ReadTestRequest;
use Domain\Test\TestFormType;
use Domain\Test\UseCase\ReadTestUseCase;
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
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_READ')", statusCode=401)
     */
    public function read(ReadTestUseCase \$useCase, string \$id): Response
    {
        \$request = new ReadTestRequest(\$id);

        \$response = \$useCase->execute(\$request);

        if (!\$response->test->id()) {
            throw \$this->createNotFoundException();
        }

        return \$this->render('test/show.html.twig', [
            'test' => \$response->test,
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

        // @codingStandardsIgnoreStarts
        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace App\Controller;

use Domain\Test\Request\UpdateTestRequest;
use Domain\Test\TestFormType;
use Domain\Test\UseCase\UpdateTestUseCase;
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
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_UPDATE')", statusCode=401)
     */
    public function edit(ReadTestUseCase \$useCase, int \$id): Response
    {
        \$request = new ReadTestRequest(\$id);

        \$response = \$useCase->execute(\$request);

        if (!\$response->test->foo()) {
            throw \$this->createNotFoundException();
        }

        \$form = \$this->createForm(TestFormType::class, \$response->test);

        return \$this->render('test/edit.html.twig', [
            'test' => \$response->test,
            'form' => \$form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", methods={"PUT"}, name="tests.update")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_UPDATE')", statusCode=401)
     */
    public function update(Request \$request, UpdateTestUseCase \$useCase, TranslatorInterface \$trans, int \$id): Response
    {
        \$form = \$this->createForm(TestFormType::class);
        \$form->submit(\$request->request->get(\$form->getName()));
        \$form->handleRequest(\$request);

        \$request = new UpdateTestRequest(\$id, \$form->getData());

        \$useCase->execute(\$request);

        \$this->addFlash('success', \$trans->trans('message.updated', [], 'test'));

        return \$this->redirectToRoute('tests.index');
    }
}

T
, $render->build());
        // @codingStandardsIgnoreEnd
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
{$this->header}
namespace App\Controller;

use Domain\Test\Request\DeleteTestRequest;
use Domain\Test\TestFormType;
use Domain\Test\UseCase\DeleteTestUseCase;
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
    public function delete(DeleteTestUseCase \$useCase, TranslatorInterface \$trans, int \$id): Response
    {
        \$request = new DeleteTestRequest(\$id);

        \$useCase->execute(\$request);

        \$this->addFlash('success', \$trans->trans('message.deleted', [], 'test'));

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
{$this->header}
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
        $render = new ControllerBuilder($this->getSchemaFkRelation('UserPosts'), []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);
{$this->header}
namespace App\Controller;

use Domain\UserPost\Request\FindUserPostBarRequest;
use Domain\UserPost\Request\FindUserPostPostRequest;
use Domain\UserPost\Request\FindUserPostUserStatusRequest;
use Domain\UserPost\UseCase\FindUserPostBarUseCase;
use Domain\UserPost\UseCase\FindUserPostPostUseCase;
use Domain\UserPost\UseCase\FindUserPostUserStatusUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user-posts")
 */
final class UserPostController extends AbstractController
{
    /**
     * @Route("/find-bars", methods={"POST"}, name="user-posts.find.bars")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BAR_INDEX')", statusCode=401)
     */
    public function findBar(Request \$request, FindUserPostBarUseCase \$useCase): Response
    {
        if (!\$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        \$request = new FindUserPostBarRequest(\$request->request->all());

        \$response = \$useCase->execute(\$request);

        return new JsonResponse([
            'results' => \$response->bars,
            'pagination' => ['more' => false],
        ]);
    }

    /**
     * @Route("/find-posts", methods={"POST"}, name="user-posts.find.posts")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_POST_INDEX')", statusCode=401)
     */
    public function findPost(Request \$request, FindUserPostPostUseCase \$useCase): Response
    {
        if (!\$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        \$request = new FindUserPostPostRequest(\$request->request->all());

        \$response = \$useCase->execute(\$request);

        return new JsonResponse([
            'results' => \$response->posts,
            'pagination' => ['more' => false],
        ]);
    }

    /**
     * @Route("/find-user-status", methods={"POST"}, name="user-posts.find.user-status")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_USERSTATUS_INDEX')", statusCode=401)
     */
    public function findUserStatus(Request \$request, FindUserPostUserStatusUseCase \$useCase): Response
    {
        if (!\$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        \$request = new FindUserPostUserStatusRequest(\$request->request->all());

        \$response = \$useCase->execute(\$request);

        return new JsonResponse([
            'results' => \$response->userStatus,
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
{$this->header}
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
{$this->header}
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
    public function action(Request \$request, ActionTestUseCase \$useCase): Response
    {
        \$request = new ActionTestRequest(\$request->request->all());

        \$response = \$useCase->execute(\$request);

        return new Response(\$response);
    }

    /**
     * @Route("/custom-fuz", methods={"POST"}, name="tests.custom-fuz")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_TEST_CUSTOMFUZ')", statusCode=401)
     */
    public function customFuz(Request \$request, CustomFuzTestUseCase \$useCase): Response
    {
        \$request = new CustomFuzTestRequest(\$request->request->all());

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
