<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Domain\UseCases;

use FlexPHP\Generator\Domain\Constants\Keyword;
use FlexPHP\Generator\Domain\Messages\Requests\MakeConstraintRequest;
use FlexPHP\Generator\Domain\Messages\Requests\MakeControllerRequest;
use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\Messages\Responses\MakeConstraintResponse;
use FlexPHP\Generator\Domain\Messages\Responses\MakeControllerResponse;
use FlexPHP\Generator\Domain\Messages\Responses\SheetProcessResponse;
use FlexPHP\UseCases\UseCase;
use Symfony\Component\Yaml\Yaml;

class SheetProcessUseCase extends UseCase
{
    /**
     * Process sheet
     *
     * @param SheetProcessRequest $request
     *
     * @return SheetProcessResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, SheetProcessRequest::class, $request);

        $name = $request->name;
        $path = $request->path;
        $constraints = [];

        $sheet = Yaml::parse((string)\file_get_contents($path));

        foreach ($sheet[$name]['Attributes'] as $attribute => $properties) {
            $constraints[$attribute] = $properties[Keyword::CONSTRAINTS] ?? null;
        }

        $controller = $this->makeController($name);
        $constraint = $this->makeConstraint($name, $constraints);

        return new SheetProcessResponse([
            'controller' => $controller->file,
            'constraint' => $constraint->file,
        ]);
    }

    private function makeController(string $name): MakeControllerResponse
    {
        return (new MakeControllerUseCase())->execute(
            new MakeControllerRequest($name, [
                'index',
                'create',
                'read',
                'update',
                'delete',
            ])
        );
    }

    private function makeConstraint(string $name, array $properties): MakeConstraintResponse
    {
        return (new MakeConstraintUseCase())->execute(
            new MakeConstraintRequest($name, $properties)
        );
    }
}
