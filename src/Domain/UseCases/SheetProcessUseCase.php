<?php

namespace FlexPHP\Generator\Domain\UseCases;

use FlexPHP\Generator\Domain\Messages\Requests\MakeControllerRequest;
use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\Messages\Responses\SheetProcessResponse;
use FlexPHP\UseCases\UseCase;
use Symfony\Component\Yaml\Yaml;

class SheetProcessUseCase extends UseCase
{
    /**
     * Process sheet
     *
     * @param SheetProcessRequest $request
     * @return SheetProcessResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, SheetProcessRequest::class, $request);

        $name = $request->name;
        $path = $request->path;

        $sheet = Yaml::parse((string)\file_get_contents($path));

        $makeController = new MakeControllerUseCase();
        $responseController = $makeController->execute(
            new MakeControllerRequest($name, [
                'index',
                'create',
                'read',
                'update',
                'delete',
            ])
        );

        return new SheetProcessResponse([
            'controller' => $responseController->file,
        ]);
    }
}
