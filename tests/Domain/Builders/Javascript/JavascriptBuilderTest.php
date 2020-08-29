<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Javascript;

use FlexPHP\Generator\Domain\Builders\Javascript\JavascriptBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class JavascriptBuilderTest extends TestCase
{
    public function testItOk(): void
    {
        $render = new JavascriptBuilder($this->getSchema());

        $this->assertEquals(<<<T
jQuery(document).ready(function ($) {
    'use strict';

});

T
, $render->build());
    }

    public function testItFkRelationsOk(): void
    {
        $render = new JavascriptBuilder($this->getSchemaFkRelation('PostComments'));

        $this->assertEquals(<<<T
jQuery(document).ready(function ($) {
    'use strict';

    const fooUrl = $('[id$=form_foo]').data('autocomplete-url');
    const postIdUrl = $('[id$=form_postId]').data('autocomplete-url');
    const statusIdUrl = $('[id$=form_statusId]').data('autocomplete-url');

    $('[id$=form_foo]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 3,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: fooUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });

    $('[id$=form_postId]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 3,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: postIdUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });

    $('[id$=form_statusId]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 3,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: statusIdUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });
});

T
, $render->build());
    }

    public function testItBlameByOk(): void
    {
        $render = new JavascriptBuilder($this->getSchemaStringAndBlameBy('Test'));

        $this->assertEquals(<<<T
jQuery(document).ready(function ($) {
    'use strict';

});

T
, $render->build());
    }
}
