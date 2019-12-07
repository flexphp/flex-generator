<?php

namespace FlexPHP\Generator\Domain\UseCases;

use FlexPHP\Generator\Domain\Messages\Requests\CreatePrototypeRequest;
use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreatePrototypeResponse;
use FlexPHP\Generator\Domain\Messages\Responses\SheetProcessResponse;
use FlexPHP\UseCases\UseCase;

class CreatePrototypeUseCase extends UseCase
{
    /**
     * @param CreatePrototypeRequest $request
     * @return CreatePrototypeResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreatePrototypeRequest::class, $request);

        $sheets = $request->sheets;

        foreach ($sheets as $name => $fileConfig) {
            $this->processSheet($name, $fileConfig);
        }

        $this->addVendorFiles();

        return new CreatePrototypeResponse();
    }

    private function processSheet(string $name, $fileConfig): SheetProcessResponse
    {
        return (new SheetProcessUseCase())->execute(
            new SheetProcessRequest($name, $fileConfig),
        );
    }

    private function addVendorFiles()
    {
        copy(
            __DIR__ . '/../BoilerPlates/Symfony/v43/composer.json',
            __DIR__ . '/../../tmp/skeleton/composer.json'
        );
    }
}
