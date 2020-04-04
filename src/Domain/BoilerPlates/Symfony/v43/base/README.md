# Demo

[![Latest Stable Version](https://poser.pugx.org/flexphp/demo/v/stable)](https://packagist.org/packages/flexphp/demo)
[![Total Downloads](https://poser.pugx.org/flexphp/demo/downloads)](https://packagist.org/packages/flexphp/demo)
[![Latest Unstable Version](https://poser.pugx.org/flexphp/demo/v/unstable)](https://packagist.org/packages/flexphp/demo)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/flexphp/flex-demo/badges/quality-score.png)](https://scrutinizer-ci.com/g/flexphp/flex-demo)
[![Code Coverage](https://scrutinizer-ci.com/g/flexphp/flex-demo/badges/coverage.png)](https://scrutinizer-ci.com/g/flexphp/flex-demo)
[![License](https://poser.pugx.org/flexphp/demo/license)](https://packagist.org/packages/flexphp/demo)
[![composer.lock](https://poser.pugx.org/flexphp/demo/composerlock)](https://packagist.org/packages/flexphp/demo)

Flex PHP to Any Framework

Change between frameworks when you need. Keep It Simple, SOLID and DRY with FlexPHP.

## Installation

Install the package with Composer:

```bash
composer install
```

After install check [web server configuration](https://symfony.com/doc/current/setup/web_server_configuration.html)

or, Apache conf file add:

```
Alias /demo/ "/var/www/html/flexphp/flex-demo/public/"
<Directory "/var/www/html/flexphp/flex-demo/public">
    AllowOverride None
    DirectoryIndex index.php

    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    <IfModule mod_rewrite.c>
        RewriteEngine On

        RewriteCond %{REQUEST_URI}::$0 ^(/.+)/(.*)::\2$
        RewriteRule .* - [E=BASE:%1]

        RewriteCond %{HTTP:Authorization} .+
        RewriteRule ^ - [E=HTTP_AUTHORIZATION:%0]

        RewriteCond %{ENV:REDIRECT_STATUS} =""
        RewriteRule ^index\.php(?:/(.*)|$) %{ENV:BASE}/$1 [R=301,L]

        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ %{ENV:BASE}/index.php [L]
    </IfModule>

    <IfModule !mod_rewrite.c>
        <IfModule mod_alias.c>
            RedirectMatch 307 ^/$ /index.php/
        </IfModule>
    </IfModule>

    FallbackResource /index.php
</Directory>

<Directory /var/www/project/public/bundles>
    FallbackResource disabled
</Directory>
```

## License

Generator is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
