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
use FlexPHP\Generator\Domain\Messages\Requests\CreateMenuFileRequest;
use FlexPHP\Generator\Domain\Messages\Requests\CreatePrototypeRequest;
use FlexPHP\Generator\Domain\Messages\Requests\SheetProcessRequest;
use FlexPHP\Generator\Domain\Messages\Responses\CreatePrototypeResponse;
use FlexPHP\Generator\Domain\Messages\Responses\SheetProcessResponse;

final class CreatePrototypeUseCase
{
    public function execute(CreatePrototypeRequest $request): CreatePrototypeResponse
    {
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
        $this->addMenuFile($outputDir, $sheets);
        $this->addFrameworkDirectories($outputDir);
        $this->addAssetFiles($outputDir);
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
            new CreateDatabaseFileRequest($platform, $name, $name, \sha1($name), $schemafiles)
        );

        \rename($database->file, $dest . '/domain/Database/create.sql');
    }

    private function addMenuFile(string $dest, array $schemafiles): void
    {
        $menu = (new CreateMenuFileUseCase())->execute(
            new CreateMenuFileRequest($schemafiles)
        );

        \rename($menu->file, $dest . '/config/menu.php');
    }

    private function addFrameworkDirectories(string $dest): void
    {
        $dirs = [
            '/bin',
            '/config',
            '/config/packages',
            '/public',
            '/public/js',
            '/public/js/bootstrap',
            '/public/js/jquery',
            '/public/js/select2',
            '/public/css',
            '/public/css/bootstrap',
            '/public/css/fontawesome',
            '/public/css/webfonts',
            '/public/css/select2',
            '/src/Command',
            '/src/Controller',
            '/src/Form',
            '/src/Form/Type',
            '/src/Listener',
            '/src/Migrations',
            '/src/Security',
            '/src/Twig',
            '/templates',
            '/templates/dashboard',
            '/templates/default',
            '/templates/form',
            '/templates/security',
            '/templates/errors',
            '/translations',
            '/var',
            '/var/cache',
            '/var/log',
            '/var/sessions',
            '/tests',
        ];

        foreach ($dirs as $dir) {
            if (!\is_dir($dest . $dir)) {
                \mkdir($dest . $dir, 0770, true);
            }
        }
    }

    private function addFrameworkFiles(string $source, string $dest): void
    {
        $templates = [
            $source . '/config/bootstrap.tphp' => $dest . '/config/bootstrap.php',
            $source . '/config/bundles.tphp' => $dest . '/config/bundles.php',
            $source . '/config/packages/security.yaml' => $dest . '/config/packages/security.yaml',
            $source . '/config/packages/translation.yaml' => $dest . '/config/packages/translation.yaml',
            $source . '/config/packages/twig.yaml' => $dest . '/config/packages/twig.yaml',
            $source . '/config/packages/framework.yaml' => $dest . '/config/packages/framework.yaml',
            $source . '/public/index.tphp' => $dest . '/public/index.php',
            $source . '/src/Kernel.tphp' => $dest . '/src/Kernel.php',
            $source . '/src/Controller/DashboardController.tphp' => $dest . '/src/Controller/DashboardController.php',
            $source . '/src/Controller/SecurityController.tphp' => $dest . '/src/Controller/SecurityController.php',
            $source . '/src/Form/Type/Select2Type.tphp' => $dest . '/src/Form/Type/Select2Type.php',
            $source . '/src/Listener/CsrfListener.tphp' => $dest . '/src/Listener/CsrfListener.php',
            $source . '/src/Security/LoginFormAuthenticator.tphp' => $dest . '/src/Security/LoginFormAuthenticator.php',
            $source . '/src/Security/UserProvider.tphp' => $dest . '/src/Security/UserProvider.php',
            $source . '/src/Twig/AppExtension.tphp' => $dest . '/src/Twig/AppExtension.php',
            $source . '/src/Twig/AppRuntime.tphp' => $dest . '/src/Twig/AppRuntime.php',
            $source . '/translations/.gitignore' => $dest . '/translations/.gitignore',
            $source . '/translations/messages.en.tphp' => $dest . '/translations/messages.en.php',
            $source . '/translations/dashboard.en.tphp' => $dest . '/translations/dashboard.en.php',
        ];

        foreach ($templates as $from => $to) {
            \copy($from, $to);
        }

        $files = [
            '/composer.json',
            '/.env.example',
            '/.gitignore',
            '/README.md',
            '/LICENSE.md',
            '/phpunit.xml',
            '/bin/console',
            '/config/services.yaml',
            '/public/robots.txt',
            '/public/.htaccess',
            '/public/favicon.ico',
            '/templates/base.html.twig',
            '/templates/dashboard/index.html.twig',
            '/templates/default/_flash.html.twig',
            '/templates/default/homepage.html.twig',
            '/templates/form/layout.html.twig',
            '/templates/form/_delete_confirmation.html.twig',
            '/templates/security/login.html.twig',
            '/templates/errors/error.html.twig',
            '/templates/errors/error403.html.twig',
            '/templates/errors/error404.html.twig',
            '/templates/errors/error500.html.twig',
        ];

        foreach ($files as $file) {
            \copy($source . $file, $dest . $file);
        }
    }

    private function addAssetFiles(string $dest): void
    {
        $src = __DIR__ . '/../BoilerPlates';

        $assets = [
            "$src/Bootstrap/css/bootstrap.min.css" => "$dest/public/css/bootstrap/bootstrap.min.css",
            "$src/Bootstrap/css/select2.min.css" => "$dest/public/  ",
            "$src/Bootstrap/css/select2bs4.min.css" => "$dest/public/css/select2/select2bs4.min.css",
            "$src/Bootstrap/main.css" => "$dest/public/css/main.css",
            "$src/FontAwesome/css/all.min.css" => "$dest/public/css/fontawesome/all.min.css",
            "$src/FontAwesome/webfonts/fonts.css" => "$dest/public/css/webfonts/fonts.css",
            "$src/FontAwesome/webfonts/fa-solid-900.woff2" => "$dest/public/css/webfonts/fa-solid-900.woff2",
            "$src/FontAwesome/webfonts/CircularStd-Book.woff" => "$dest/public/css/webfonts/CircularStd-Book.woff",
            "$src/FontAwesome/webfonts/CircularStd-Medium.woff" => "$dest/public/css/webfonts/CircularStd-Medium.woff",
            "$src/Bootstrap/js/bootstrap.min.js" => "$dest/public/js/bootstrap/bootstrap.min.js",
            "$src/jQuery/js/jquery.min.js" => "$dest/public/js/jquery/jquery.min.js",
            "$src/jQuery/plugins/jquery.slimscroll.min.js" => "$dest/public/js/jquery/jquery.slimscroll.min.js",
            "$src/jQuery/plugins/jquery.select2.min.js" => "$dest/public/js/jquery/jquery.select2.min.js",
            "$src/jQuery/main.js" => "$dest/public/js/main.js",
        ];

        foreach ($assets as $from => $to) {
            \copy($from, $to);
        }
    }
}
