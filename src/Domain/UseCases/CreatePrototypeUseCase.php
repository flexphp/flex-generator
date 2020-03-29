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

use FlexPHP\Generator\Domain\Messages\Requests\CreatePrototypeRequest;
use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreatePrototypeResponse;
use FlexPHP\Generator\Domain\Messages\Responses\SheetProcessResponse;
use FlexPHP\UseCases\UseCase;

final class CreatePrototypeUseCase extends UseCase
{
    /**
     * @param CreatePrototypeRequest $request
     *
     * @return CreatePrototypeResponse
     */
    public function execute($request)
    {
        $this->throwExceptionIfRequestNotValid(__METHOD__, CreatePrototypeRequest::class, $request);

        $sheets = $request->sheets;
        $outputFolder = $request->outputFolder;

        if (!\is_dir($outputFolder)) {
            \mkdir($outputFolder, 0777, true); // @codeCoverageIgnore
        }

        foreach ($sheets as $name => $schemafile) {
            $this->processSheet($name, $schemafile);
        }

        $this->addVendorFiles($outputFolder);

        return new CreatePrototypeResponse($outputFolder);
    }

    private function processSheet(string $name, string $schemafile): SheetProcessResponse
    {
        return (new SheetProcessUseCase())->execute(
            new SheetProcessRequest($name, $schemafile)
        );
    }

    private function addVendorFiles(string $outputFolder): void
    {
        \copy(
            __DIR__ . '/../BoilerPlates/Symfony/v43/composer.json',
            $outputFolder . '/composer.json'
        );
    }
}
