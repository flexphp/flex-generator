# Demo

Flex PHP to Any Framework

Change between frameworks when you need. Keep It Simple, SOLID and DRY with FlexPHP.

## Repository

```bash
git init
git config user.name "Freddie Gar"
git config user.email "freddie.gar@outlook.com"
git checkout -b develop
git add .
git commit -m "Initial commit"
```

## Installation

Create database

```bash
mysql -uroot -p < domain/Database/create.sql
```

Create environment and set `dbhost`, `dbport`, `dbname`, `dbuser` and `dbpassword` to database

```bash
cp -p .env.example .env
vim .env

DATABASE_URL=mysql://dbuser:dbpassword@dbhost:dbport/dbname
```

Install the package with Composer:

```bash
composer install
```

After install check [web server configuration](https://symfony.com/doc/current/setup/web_server_configuration.html)

or, Apache conf file add:

```
Alias /mak/ "/var/www/html/public/"
<Directory "/var/www/html/public">
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
