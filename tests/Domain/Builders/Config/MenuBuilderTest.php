<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Config;

use FlexPHP\Generator\Domain\Builders\Config\MenuBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class MenuBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new MenuBuilder([
            'Posts' => 'fas fa-mail-bulk',
            'comment' => 'fas fa-comments',
            'USER' => 'fas fa-users',
            'UserPassword' => 'fas fa-key',
        ]);

        $this->assertEquals(<<<T
<?php declare(strict_types=1);

return [
    'dashboard' => [
        'title' => 'title',
        'roles' => ['IS_AUTHENTICATED_FULLY'],
        'icon' => 'fas fa-chart-line',
        'route' => 'dashboard',
    ],
    'post' => [
        'title' => 'entity',
        'roles' => ['ROLE_ADMIN', 'ROLE_USER_POST_*'],
        'icon' => 'fas fa-mail-bulk',
        'children' => [
            [
                'title' => 'entity',
                'roles' => ['ROLE_ADMIN', 'ROLE_USER_POST_INDEX'],
                'icon' => 'fas fa-list-ol',
                'route' => 'posts.index',
            ],
            [
                'title' => 'title.new',
                'roles' => ['ROLE_ADMIN', 'ROLE_USER_POST_CREATE'],
                'icon' => 'fas fa-plus',
                'route' => 'posts.new',
            ],
        ],
    ],
    'comment' => [
        'title' => 'entity',
        'roles' => ['ROLE_ADMIN', 'ROLE_USER_COMMENT_*'],
        'icon' => 'fas fa-comments',
        'children' => [
            [
                'title' => 'entity',
                'roles' => ['ROLE_ADMIN', 'ROLE_USER_COMMENT_INDEX'],
                'icon' => 'fas fa-list-ol',
                'route' => 'comments.index',
            ],
            [
                'title' => 'title.new',
                'roles' => ['ROLE_ADMIN', 'ROLE_USER_COMMENT_CREATE'],
                'icon' => 'fas fa-plus',
                'route' => 'comments.new',
            ],
        ],
    ],
    'user' => [
        'title' => 'entity',
        'roles' => ['ROLE_ADMIN', 'ROLE_USER_USER_*'],
        'icon' => 'fas fa-users',
        'children' => [
            [
                'title' => 'entity',
                'roles' => ['ROLE_ADMIN', 'ROLE_USER_USER_INDEX'],
                'icon' => 'fas fa-list-ol',
                'route' => 'users.index',
            ],
            [
                'title' => 'title.new',
                'roles' => ['ROLE_ADMIN', 'ROLE_USER_USER_CREATE'],
                'icon' => 'fas fa-plus',
                'route' => 'users.new',
            ],
        ],
    ],
    'user-password' => [
        'title' => 'entity',
        'roles' => ['ROLE_ADMIN', 'ROLE_USER_USERPASSWORD_*'],
        'icon' => 'fas fa-key',
        'children' => [
            [
                'title' => 'entity',
                'roles' => ['ROLE_ADMIN', 'ROLE_USER_USERPASSWORD_INDEX'],
                'icon' => 'fas fa-list-ol',
                'route' => 'user-passwords.index',
            ],
            [
                'title' => 'title.new',
                'roles' => ['ROLE_ADMIN', 'ROLE_USER_USERPASSWORD_CREATE'],
                'icon' => 'fas fa-plus',
                'route' => 'user-passwords.new',
            ],
        ],
    ],
];

T
, $render->build());
    }
}
