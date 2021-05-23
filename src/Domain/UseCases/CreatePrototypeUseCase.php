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
        $sourceDir = realpath(__DIR__ . '/../BoilerPlates/Symfony/v43/base');

        if (!\is_dir($outputDir)) {
            \mkdir($outputDir, 0777, true); // @codeCoverageIgnore
        }

        $this->addDomainDirectories($outputDir);
        $this->addDomainFiles(realpath(__DIR__ . '/../BoilerPlates/FlexPHP'), $outputDir);
        $this->addDatabaseFile($outputDir, $request->name, $request->platform, $sheets);
        $this->addMenuFile($outputDir, $sheets);
        $this->addFrameworkDirectories($outputDir);
        $this->addAssetFiles($outputDir);
        $this->addFrameworkFiles($sourceDir, $outputDir);

        foreach ($sheets as $name => $schemafile) {
            $this->processSheet($name, $schemafile);
        }

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

        \rename($database->file, $dest . '/domain/Database/1 - create.sql');
    }

    private function addMenuFile(string $dest, array $schemafiles): void
    {
        $menu = (new CreateMenuFileUseCase())->execute(
            new CreateMenuFileRequest($schemafiles)
        );

        \rename($menu->file, $dest . '/config/menu.php');
    }

    private function addDomainDirectories(string $dest): void
    {
        $dirs = [
            '/config',
            '/domain',
            '/domain/Database',
            '/domain/Helper',
            '/domain/Tests',
            '/domain/User',
        ];

        foreach ($dirs as $dir) {
            if (!\is_dir($dest . $dir)) {
                \mkdir($dest . $dir, 0770, true);
            }
        }
    }

    private function addDomainFiles(string $source, string $dest): void
    {
        $templates = [
            $source . '/Extras/Helper/DateTimeTrait.tphp' => $dest . '/domain/Helper/DateTimeTrait.php',
            $source . '/Extras/Helper/DbalCriteriaHelper.tphp' => $dest . '/domain/Helper/DbalCriteriaHelper.php',
            $source . '/Extras/Tests/TestCase.tphp' => $dest . '/domain/Tests/TestCase.php',
            $source . '/Extras/Tests/DatabaseTrait.tphp' => $dest . '/domain/Tests/DatabaseTrait.php',
            $source . '/Extras/User/UserRbac.tphp' => $dest . '/domain/User/UserRbac.php',
        ];

        foreach ($templates as $from => $to) {
            \copy($from, $to);
        }

        // $files = [
        //     '/templates/errors/error500.html.twig',
        // ];

        // foreach ($files as $file) {
        //     \copy($source . $file, $dest . $file);
        // }
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
            '/public/js/jquery/i18n',
            '/public/js/select2',
            '/public/css',
            '/public/css/bootstrap',
            '/public/css/fontawesome',
            '/public/css/webfonts',
            '/public/css/select2',
            '/public/css/datepicker',
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
            $source . '/config/preload.tphp' => $dest . '/config/preload.php',
            $source . '/config/packages/security.yaml' => $dest . '/config/packages/security.yaml',
            $source . '/config/packages/translation.yaml' => $dest . '/config/packages/translation.yaml',
            $source . '/config/packages/twig.yaml' => $dest . '/config/packages/twig.yaml',
            $source . '/config/packages/framework.yaml' => $dest . '/config/packages/framework.yaml',
            $source . '/public/index.tphp' => $dest . '/public/index.php',
            $source . '/src/Kernel.tphp' => $dest . '/src/Kernel.php',
            $source . '/src/Controller/.gitignore' => $dest . '/src/Controller/.gitignore',
            $source . '/src/Controller/DashboardController.tphp' => $dest . '/src/Controller/DashboardController.php',
            $source . '/src/Controller/LocaleController.tphp' => $dest . '/src/Controller/LocaleController.php',
            $source . '/src/Controller/SecurityController.tphp' => $dest . '/src/Controller/SecurityController.php',
            $source . '/src/Controller/HomepageController.tphp' => $dest . '/src/Controller/HomepageController.php',
            $source . '/src/Form/Type/Select2Type.tphp' => $dest . '/src/Form/Type/Select2Type.php',
            $source . '/src/Form/Type/DatepickerType.tphp' => $dest . '/src/Form/Type/DatepickerType.php',
            $source . '/src/Form/Type/DatetimepickerType.tphp' => $dest . '/src/Form/Type/DatetimepickerType.php',
            $source . '/src/Form/Type/TimepickerType.tphp' => $dest . '/src/Form/Type/TimepickerType.php',
            $source . '/src/Form/Type/DatestartpickerType.tphp' => $dest . '/src/Form/Type/DatestartpickerType.php',
            $source . '/src/Form/Type/DatefinishpickerType.tphp' => $dest . '/src/Form/Type/DatefinishpickerType.php',
            $source . '/src/Listener/CsrfListener.tphp' => $dest . '/src/Listener/CsrfListener.php',
            $source . '/src/Listener/LocaleListener.tphp' => $dest . '/src/Listener/LocaleListener.php',
            $source . '/src/Listener/ExceptionListener.tphp' => $dest . '/src/Listener/ExceptionListener.php',
            $source . '/src/Security/LoginFormAuthenticator.tphp' => $dest . '/src/Security/LoginFormAuthenticator.php',
            $source . '/src/Security/UserProvider.tphp' => $dest . '/src/Security/UserProvider.php',
            $source . '/src/Twig/AppExtension.tphp' => $dest . '/src/Twig/AppExtension.php',
            $source . '/src/Twig/AppRuntime.tphp' => $dest . '/src/Twig/AppRuntime.php',
            $source . '/translations/.gitignore' => $dest . '/translations/.gitignore',
            $source . '/translations/dashboard.en.tphp' => $dest . '/translations/dashboard.en.php',
            $source . '/translations/dashboard.es.tphp' => $dest . '/translations/dashboard.es.php',
            $source . '/translations/login.en.tphp' => $dest . '/translations/login.en.php',
            $source . '/translations/login.es.tphp' => $dest . '/translations/login.es.php',
            $source . '/translations/messages.en.tphp' => $dest . '/translations/messages.en.php',
            $source . '/translations/messages.es.tphp' => $dest . '/translations/messages.es.php',
            $source . '/translations/error.en.tphp' => $dest . '/translations/error.en.php',
            $source . '/translations/error.es.tphp' => $dest . '/translations/error.es.php',
            $source . '/var/cache/.gitkeep' => $dest . '/var/cache/.gitkeep',
            $source . '/var/log/.gitkeep' => $dest . '/var/log/.gitkeep',
            $source . '/var/sessions/.gitkeep' => $dest . '/var/sessions/.gitkeep',
            $source . '/tests/.gitkeep' => $dest . '/tests/.gitkeep',
            $source . '/tests/TestCase.tphp' => $dest . '/tests/TestCase.php',
            $source . '/tests/WebTestCase.tphp' => $dest . '/tests/WebTestCase.php',
        ];

        foreach ($templates as $from => $to) {
            \copy($from, $to);
        }

        $files = [
            '/composer.json',
            '/.htaccess',
            '/.env.example',
            '/.env.test.example',
            '/.gitignore',
            '/README.md',
            '/LICENSE.md',
            '/CHANGELOG.md',
            '/phpunit.xml.dist',
            '/bin/console',
            '/config/routes.yaml',
            '/config/services.yaml',
            '/public/robots.txt',
            '/public/.htaccess',
            '/public/favicon.ico',
            '/templates/base.html.twig',
            '/templates/dashboard/index.html.twig',
            '/templates/default/_flash.html.twig',
            '/templates/default/_infinite.html.twig',
            '/templates/default/_back_button.html.twig',
            '/templates/default/_filter_button.html.twig',
            '/templates/default/homepage.html.twig',
            '/templates/form/layout.html.twig',
            '/templates/form/_delete_confirmation.html.twig',
            '/templates/form/fields.html.twig',
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
            "$src/Bootstrap/css/select2.min.css" => "$dest/public/css/select2/select2.min.css",
            "$src/Bootstrap/css/select2bs4.min.css" => "$dest/public/css/select2/select2bs4.min.css",
            "$src/Bootstrap/css/datepicker.min.css" => "$dest/public/css/datepicker/datepicker.min.css",
            "$src/Bootstrap/main.css" => "$dest/public/css/main.css",
            "$src/Bootstrap/images/icons.png" => "$dest/public/css/datepicker/icons.png",
            "$src/FontAwesome/css/all.min.css" => "$dest/public/css/fontawesome/all.min.css",
            "$src/FontAwesome/webfonts/fonts.css" => "$dest/public/css/webfonts/fonts.css",
            "$src/FontAwesome/webfonts/fa-solid-900.woff2" => "$dest/public/css/webfonts/fa-solid-900.woff2",
            "$src/FontAwesome/webfonts/fa-regular-400.woff2" => "$dest/public/css/webfonts/fa-regular-400.woff2",
            "$src/FontAwesome/webfonts/CircularStd-Book.woff" => "$dest/public/css/webfonts/CircularStd-Book.woff",
            "$src/FontAwesome/webfonts/CircularStd-Medium.woff" => "$dest/public/css/webfonts/CircularStd-Medium.woff",
            "$src/Bootstrap/js/bootstrap.min.js" => "$dest/public/js/bootstrap/bootstrap.min.js",
            "$src/jQuery/js/jquery.min.js" => "$dest/public/js/jquery/jquery.min.js",
            "$src/jQuery/plugins/jquery.slimscroll.min.js" => "$dest/public/js/jquery/jquery.slimscroll.min.js",
            "$src/jQuery/plugins/jquery.select2.min.js" => "$dest/public/js/jquery/jquery.select2.min.js",
            "$src/jQuery/plugins/jquery.infinite.min.js" => "$dest/public/js/jquery/jquery.infinite.min.js",
            "$src/jQuery/plugins/jquery.datepicker.min.js" => "$dest/public/js/jquery/jquery.datepicker.min.js",
            "$src/jQuery/plugins/chart.bundle.min.js" => "$dest/public/js/jquery/chart.bundle.min.js",
            "$src/jQuery/main.js" => "$dest/public/js/main.js",
            "$src/jQuery/i18n" => "$dest/public/js/jquery/i18n",
        ];

        foreach ($assets as $from => $to) {
            if (\is_dir($from)) {
                $dir = \opendir($from);

                while (false !== ($file = \readdir($dir))) {
                    if (!\in_array($file, ['.', '..'])) {
                        \copy($from . '/' . $file, $to . '/' . $file);
                    }
                }

                \closedir($dir);

                continue;
            }

            \copy($from, $to);
        }
    }
}
