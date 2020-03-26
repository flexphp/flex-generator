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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 */
class TestController extends AbstractController
{
}

T, $render->build());
    }

    public function testItRenderIndexOk(): void
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

use Domain\Test\Message\IndexTestRequest;
use Domain\Test\Message\CustomFuzTestRequest;
use Domain\Test\UseCase\IndexTestUseCase;
use Domain\Test\UseCase\CustomFuzTestUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/"}, methods={"GET"}, name="test.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
    {
        \$requestMessage = new IndexTestRequest(\$request->request->all());

        \$useCase = new IndexTestUseCase();
        \$response = \$useCase->execute(\$requestMessage);

        return new Response(\$response);
    }

    /**
     * @Route("/custom_fuz"}, methods={"POST"}, name="test.custom_fuz")
     */
    public function customFuz(Request \$request): Response
    {
        \$requestMessage = new CustomFuzTestRequest(\$request->request->all());

        \$useCase = new CustomFuzTestUseCase();
        \$response = \$useCase->execute(\$requestMessage);

        return new Response(\$response);
    }
}

T, $render->build());
    }
}
