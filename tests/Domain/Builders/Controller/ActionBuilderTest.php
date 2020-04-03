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

final class ActionBuilderTest extends TestCase
{
    /**
     * @dataProvider getEntityAndRouteName
     */
    public function testItRenderOk(string $entity, string $route): void
    {
        $render = new ActionBuilder($entity, '');

        $this->assertEquals(<<<T
    /**
     * @Route("/", methods={"GET"}, name="{$route}.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderIndexOk(): void
    {
        $render = new ActionBuilder('Test', 'index');

        $this->assertEquals(<<<T
    /**
     * @Route("/", methods={"GET"}, name="tests.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new ActionBuilder('Test', 'create');

        $this->assertEquals(<<<T
    /**
     * @Route("/create", methods={"POST"}, name="tests.create")
     */
    public function create(Request \$request): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new ActionBuilder('Test', 'read');

        $this->assertEquals(<<<T
    /**
     * @Route("/{id}", methods={"GET"}, name="tests.read")
     * @Cache(smaxage="10")
     */
    public function read(\$id): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new ActionBuilder('Test', 'update');

        $this->assertEquals(<<<T
    /**
     * @Route("/update/{id}", methods={"PUT"}, name="tests.update")
     */
    public function update(Request \$request, \$id): Response
    {
    }

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new ActionBuilder('Test', 'delete');

        $this->assertEquals(<<<T
    /**
     * @Route("/delete/{id}", methods={"DELETE"}, name="tests.delete")
     */
    public function delete(\$id): Response
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
        $render = new ActionBuilder('FooBar', $action);

        $this->assertEquals(<<<T
    /**
     * @Route("/custom-action", methods={"POST"}, name="foo-bars.custom-action")
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

        $render = new ActionBuilder('Test', 'index', $requestMessage);

        $this->assertEquals(<<<T
    /**
     * @Route("/", methods={"GET"}, name="tests.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
    {
        $requestMessage
    }

T
, $render->build());
    }

    public function testItRenderWithUseCase(): void
    {
        $useCase = '// bar';

        $render = new ActionBuilder('Test', 'index', $useCase);

        $this->assertEquals(<<<T
    /**
     * @Route("/", methods={"GET"}, name="tests.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
    {
        $useCase
    }

T
, $render->build());
    }

    public function testItRenderWithResponseMessage(): void
    {
        $responseMessage = '// buz';

        $render = new ActionBuilder('Test', 'index', $responseMessage);

        $this->assertEquals(<<<T
    /**
     * @Route("/", methods={"GET"}, name="tests.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
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

        $render = new ActionBuilder('Test', 'index', $requestMessage, $useCase, $responseMessage);

        $this->assertEquals(<<<T
    /**
     * @Route("/", methods={"GET"}, name="tests.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
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
        $render = new ActionBuilder('Test', 'index');

        $this->assertEquals(<<<T
    /**
     * @Route("/", methods={"GET"}, name="tests.index")
     * @Cache(smaxage="10")
     */
    public function index(Request \$request): Response
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
            // entity, route
            ['userpassword', 'userpasswords'],
            ['USERPASSWORD', 'userpasswords'],
            ['UserPassword', 'user-passwords'],
            ['userPassword', 'user-passwords'],
            ['user_password', 'user-passwords'],
            ['user-password', 'user-passwords'],
            ['Posts', 'posts'],
        ];
    }
}
