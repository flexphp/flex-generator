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

use FlexPHP\Generator\Domain\Messages\Requests\CreateDatabaseFileRequest;
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
        $outputDir = $request->outputDir;
        $sourceDir = __DIR__ . '/../BoilerPlates/Symfony/v43/base';

        if (!\is_dir($outputDir)) {
            \mkdir($outputDir, 0777, true); // @codeCoverageIgnore
        }

        foreach ($sheets as $name => $schemafile) {
            $this->processSheet($name, $schemafile);
        }

        $this->addDatabaseFile($outputDir, $request->name, $request->platform, $sheets);
        $this->addFrameworkDirectories($sourceDir, $outputDir);
        $this->addFrameworkFiles($sourceDir, $outputDir);

        return new CreatePrototypeResponse($outputDir);
    }

    private function processSheet(string $name, string $schemafile): SheetProcessResponse
    {
        return (new SheetProcessUseCase())->execute(
            new SheetProcessRequest($name, $schemafile)
        );
    }

    private function addDatabaseFile(string $dest, string $name, string $platform, array $schemafiles): void
    {
        $database = (new CreateDatabaseFileUseCase())->execute(
            new CreateDatabaseFileRequest($platform, $name, $name, \uniqid((string)\time()), $schemafiles)
        );

        \rename($database->file, $dest . '/domain/Database/create.sql');
    }

    private function addFrameworkDirectories(string $source, string $dest): void
    {
        $dirs = [
            $source . '/bin', $dest . '/bin',
            $source . '/config', $dest . '/config',
            $source . '/public', $dest . '/public',
            $source . '/src/Command', $dest . '/src/Command',
            $source . '/src/Controller', $dest . '/src/Controller',
            $source . '/src/Migrations', $dest . '/src/Migrations',
            $source . '/templates', $dest . '/templates',
            $source . '/templates/default', $dest . '/templates/default',
            $source . '/templates/form', $dest . '/templates/form',
            $source . '/templates/security', $dest . '/templates/security',
            $source . '/templates/errors', $dest . '/templates/errors',
            $source . '/var', $dest . '/var',
            $source . '/var/cache', $dest . '/var/cache',
            $source . '/var/log', $dest . '/var/log',
            $source . '/var/sessions', $dest . '/var/sessions',
            $source . '/tests', $dest . '/tests',
        ];

        foreach ($dirs as $dir) {
            if (!\is_dir($dir)) {
                \mkdir($dir, 0770, true);
            }
        }
    }

    private function addFrameworkFiles(string $source, string $dest): void
    {
        $files = [
            $source . '/composer.json' => $dest . '/composer.json',
            $source . '/.env.example' => $dest . '/.env.example',
            $source . '/.gitignore' => $dest . '/.gitignore',
            $source . '/README.md' => $dest . '/README.md',
            $source . '/LICENSE.md' => $dest . '/LICENSE.md',
            $source . '/phpunit.xml' => $dest . '/phpunit.xml',
            $source . '/bin/console' => $dest . '/bin/console',
            $source . '/config/bootstrap.tphp' => $dest . '/config/bootstrap.php',
            $source . '/config/bundles.tphp' => $dest . '/config/bundles.php',
            $source . '/config/services.yaml' => $dest . '/config/services.yaml',
            $source . '/public/index.tphp' => $dest . '/public/index.php',
            $source . '/public/robots.txt' => $dest . '/public/robots.txt',
            $source . '/public/.htaccess' => $dest . '/public/.htaccess',
            $source . '/public/favicon.ico' => $dest . '/public/favicon.ico',
            $source . '/src/Kernel.tphp' => $dest . '/src/Kernel.php',
            $source . '/templates/base.html.twig' => $dest . '/templates/base.html.twig',
            $source . '/templates/default/_flash.html.twig' => $dest . '/templates/default/_flash.html.twig',
            $source . '/templates/default/homepage.html.twig' => $dest . '/templates/default/homepage.html.twig',
            $source . '/templates/form/layout.html.twig' => $dest . '/templates/form/layout.html.twig',
            $source . '/templates/security/login.html.twig' => $dest . '/templates/security/login.html.twig',
            $source . '/templates/errors/error.html.twig' => $dest . '/templates/errors/error.html.twig',
            $source . '/templates/errors/error403.html.twig' => $dest . '/templates/errors/error403.html.twig',
            $source . '/templates/errors/error404.html.twig' => $dest . '/templates/errors/error404.html.twig',
            $source . '/templates/errors/error500.html.twig' => $dest . '/templates/errors/error500.html.twig',
        ];

        foreach ($files as $source => $dest) {
            \copy($source, $dest);
        }
    }
}
