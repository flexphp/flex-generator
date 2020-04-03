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

final class ControllerBuilderTest extends TestCase
{
    public function testItRenderOk(): void
    {
        $entity = 'Test';

        $render = new ControllerBuilder($entity, []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
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
    public function testItOkWithDiffNameEntity(string $entity, string $expectedName, string $expectedRoute): void
    {
        $render = new ControllerBuilder($entity, []);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
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

    public function testItRenderMixedOk(): void
    {
        $entity = 'Test';
        $actions = [
            'index' => (new ActionBuilder(
                $entity,
                'index',
                (new RequestMessageBuilder($entity, 'index'))->build(),
                (new UseCaseBuilder($entity, 'index'))->build(),
                (new ResponseMessageBuilder($entity, 'index'))->build()
            ))->build(),
            'custom Fuz' => (new ActionBuilder(
                $entity,
                'custom Fuz',
                (new RequestMessageBuilder($entity, 'custom Fuz'))->build(),
                (new UseCaseBuilder($entity, 'custom Fuz'))->build(),
                (new ResponseMessageBuilder($entity, 'custom Fuz'))->build()
            ))->build(),
        ];

        $render = new ControllerBuilder($entity, $actions);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

namespace App\Controller;

use Domain\Test\Request\IndexTestRequest;
use Domain\Test\Request\CustomFuzTestRequest;
use Domain\Test\UseCase\IndexTestUseCase;
use Domain\Test\UseCase\CustomFuzTestUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
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
     */
    public function index(Request \$request): Response
    {
        \$request = new IndexTestRequest(\$request->request->all());

        \$useCase = new IndexTestUseCase();
        \$response = \$useCase->execute(\$request);

        return new Response(\$response);
    }

    /**
     * @Route("/custom-fuz", methods={"POST"}, name="tests.custom-fuz")
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
            ['user-password', 'UserPassword', 'user-passwords'],
            ['Posts', 'Post', 'posts'],
        ];
    }
}
