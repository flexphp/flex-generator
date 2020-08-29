<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Generator\Tests\Domain\Builders\Template;

use FlexPHP\Generator\Domain\Builders\Template\TemplateBuilder;
use FlexPHP\Generator\Tests\TestCase;

final class TemplateBuilderTest extends TestCase
{
    public function testItRenderIndexOk(): void
    {
        $render = new TemplateBuilder($this->getSchema(), 'index');

        $this->assertEquals(<<<T
{% trans_default_domain 'test' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}title.list{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}title.list{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.new') }}" class="btn btn-primary">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span class="btn-text">{% trans %}title.new{% endtrans %}</span>
                </a>
            </div>
        </div>
        <div class="card-body p-0 table-responsive-sm">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">{% trans %}label.lower{% endtrans %}</th>
                        <th scope="col">{% trans %}label.upper{% endtrans %}</th>
                        <th scope="col">{% trans %}label.pascalCase{% endtrans %}</th>
                        <th scope="col">{% trans %}label.camelCase{% endtrans %}</th>
                        <th scope="col">{% trans %}label.snakeCase{% endtrans %}</th>
                        <th scope="col" class="text-center" style="width: 105px;"><i class="fa fa-cogs" aria-hidden="true"></i></th>
                    </tr>
                </thead>
                <tbody>
                {{ include('test/_ajax.html.twig', {tests: tests}) }}
                </tbody>
            </table>
            {{ include('default/_infinite.html.twig') }}
        </div>
    </div>
{% endblock %}

{% block javascripts %}
    <script src="{{ asset('js/jquery/jquery.infinite.min.js') }}"></script>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderIndexBlameAtOk(): void
    {
        $render = new TemplateBuilder($this->getSchemaAiAndBlameAt('UserStatus'), 'index');

        $this->assertEquals(<<<T
{% trans_default_domain 'userStatus' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}title.list{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}title.list{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('user-status.new') }}" class="btn btn-primary">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span class="btn-text">{% trans %}title.new{% endtrans %}</span>
                </a>
            </div>
        </div>
        <div class="card-body p-0 table-responsive-sm">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">{% trans %}label.key{% endtrans %}</th>
                        <th scope="col">{% trans %}label.value{% endtrans %}</th>
                        <th scope="col" class="text-center" style="width: 105px;"><i class="fa fa-cogs" aria-hidden="true"></i></th>
                    </tr>
                </thead>
                <tbody>
                {{ include('userStatus/_ajax.html.twig', {userStatus: userStatus}) }}
                </tbody>
            </table>
            {{ include('default/_infinite.html.twig') }}
        </div>
    </div>
{% endblock %}

{% block javascripts %}
    <script src="{{ asset('js/jquery/jquery.infinite.min.js') }}"></script>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderIndexBlameByOk(): void
    {
        $render = new TemplateBuilder($this->getSchemaStringAndBlameBy(), 'index');

        $this->assertEquals(<<<T
{% trans_default_domain 'test' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}title.list{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}title.list{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.new') }}" class="btn btn-primary">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span class="btn-text">{% trans %}title.new{% endtrans %}</span>
                </a>
            </div>
        </div>
        <div class="card-body p-0 table-responsive-sm">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">{% trans %}label.code{% endtrans %}</th>
                        <th scope="col">{% trans %}label.name{% endtrans %}</th>
                        <th scope="col" class="text-center" style="width: 105px;"><i class="fa fa-cogs" aria-hidden="true"></i></th>
                    </tr>
                </thead>
                <tbody>
                {{ include('test/_ajax.html.twig', {tests: tests}) }}
                </tbody>
            </table>
            {{ include('default/_infinite.html.twig') }}
        </div>
    </div>
{% endblock %}

{% block javascripts %}
    <script src="{{ asset('js/jquery/jquery.infinite.min.js') }}"></script>
{% endblock %}

T, $render->build());
    }

    public function testItRenderIndexAjaxOk(): void
    {
        $render = new TemplateBuilder($this->getSchema(), 'ajax');

        $this->assertEquals(<<<T
{% for _test in tests %}
    <tr>
        <td>{{ _test.lower }}</td>
        <td>{{ _test.upper }}</td>
        <td>{{ _test.pascalCase ? _test.pascalCase|date('Y-m-d H:i:s') : '-' }}</td>
        <td>{% if _test.camelCase %}{% trans from 'messages' %}label.yes{% endtrans %}{% else %}{% trans from 'messages' %}label.no{% endtrans %}{% endif %}</td>
        <td>{{ _test.snakeCase }}</td>
        <td class="text-center">
            <div class="btn-group">
                <a href="{{ path('tests.read', {id: _test.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.read{% endtrans %}">
                    <i class="fa fa-eye text-dark" aria-hidden="true"></i>
                </a>

                <a href="{{ path('tests.edit', {id: _test.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.edit{% endtrans %}">
                    <i class="fa fa-edit text-info" aria-hidden="true"></i>
                </a>
            </div>
        </td>
    </tr>
{% endfor %}

T, $render->build());
    }

    public function testItRenderIndexAjaxBlameAtOk(): void
    {
        $render = new TemplateBuilder($this->getSchemaAiAndBlameAt('UserStatus'), 'ajax');

        $this->assertEquals(<<<T
{% for _userStatus in userStatus %}
    <tr>
        <td>{{ _userStatus.key }}</td>
        <td>{{ _userStatus.value }}</td>
        <td class="text-center">
            <div class="btn-group">
                <a href="{{ path('user-status.read', {id: _userStatus.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.read{% endtrans %}">
                    <i class="fa fa-eye text-dark" aria-hidden="true"></i>
                </a>

                <a href="{{ path('user-status.edit', {id: _userStatus.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.edit{% endtrans %}">
                    <i class="fa fa-edit text-info" aria-hidden="true"></i>
                </a>
            </div>
        </td>
    </tr>
{% endfor %}

T, $render->build());
    }

    public function testItRenderIndexAjaxBlameByOk(): void
    {
        $render = new TemplateBuilder($this->getSchemaStringAndBlameBy(), 'ajax');

        $this->assertEquals(<<<T
{% for _test in tests %}
    <tr>
        <td>{{ _test.code }}</td>
        <td>{{ _test.name }}</td>
        <td class="text-center">
            <div class="btn-group">
                <a href="{{ path('tests.read', {id: _test.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.read{% endtrans %}">
                    <i class="fa fa-eye text-dark" aria-hidden="true"></i>
                </a>

                <a href="{{ path('tests.edit', {id: _test.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.edit{% endtrans %}">
                    <i class="fa fa-edit text-info" aria-hidden="true"></i>
                </a>
            </div>
        </td>
    </tr>
{% endfor %}

T, $render->build());
    }

    public function testItRenderIndexAjaxFkRelationsOk(): void
    {
        $render = new TemplateBuilder($this->getSchemaFkRelation(), 'ajax');

        $this->assertEquals(<<<T
{% for _test in tests %}
    <tr>
        <td>{{ _test.pk }}</td>
        <td>{{ _test.fooInstance.fuz|default('-') }}</td>
        <td>{{ _test.postIdInstance.name|default('-') }}</td>
        <td>{{ _test.statusIdInstance.name|default('-') }}</td>
        <td class="text-center">
            <div class="btn-group">
                <a href="{{ path('tests.read', {id: _test.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.read{% endtrans %}">
                    <i class="fa fa-eye text-dark" aria-hidden="true"></i>
                </a>

                <a href="{{ path('tests.edit', {id: _test.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.edit{% endtrans %}">
                    <i class="fa fa-edit text-info" aria-hidden="true"></i>
                </a>
            </div>
        </td>
    </tr>
{% endfor %}

T, $render->build());
    }

    public function testItRenderCreateOk(): void
    {
        $render = new TemplateBuilder($this->getSchema(), 'create');

        $this->assertEquals(<<<T
{% trans_default_domain 'test' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}title.new{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}title.new{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                    <span class="btn-text">{% trans from 'messages' %}action.list{% endtrans %}</span>
                </a>
            </div>
        </div>

        {{ form_start(form, {'action': path('tests.create')}) }}
            <div class="card-body">
                {{ form_widget(form) }}
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    <span class="btn-text">{% trans from 'messages' %}action.create{% endtrans %}</span>
                </button>
            </div>
        {{ form_end(form) }}
    </div>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderCreateFkRelationsOk(): void
    {
        $render = new TemplateBuilder($this->getSchemaFkRelation('PostComments'), 'create');

        $this->assertEquals(<<<T
{% trans_default_domain 'postComment' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}title.new{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}title.new{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('post-comments.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                    <span class="btn-text">{% trans from 'messages' %}action.list{% endtrans %}</span>
                </a>
            </div>
        </div>

        {{ form_start(form, {'action': path('post-comments.create')}) }}
            <div class="card-body">
                {{ form_widget(form) }}
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    <span class="btn-text">{% trans from 'messages' %}action.create{% endtrans %}</span>
                </button>
            </div>
        {{ form_end(form) }}
    </div>
{% endblock %}

{% block stylesheets %}
    <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/select2/select2bs4.min.css') }}" />
{% endblock %}

{% block javascripts %}
    <script src="{{ asset('js/jquery/jquery.select2.min.js') }}"></script>
    <script src="{{ asset('js/postComments.js') }}"></script>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new TemplateBuilder($this->getSchema(), 'read');

        $this->assertEquals(<<<T
{% trans_default_domain 'test' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}title.show{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}entity{% endtrans %}: {{ test.lower }}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                    <span class="btn-text">{% trans from 'messages' %}action.list{% endtrans %}</span>
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="form-group"><label>{% trans %}label.upper{% endtrans %}</label><div class="form-control-plaintext">{{ test.upper }}</div></div>
            <div class="form-group"><label>{% trans %}label.pascalCase{% endtrans %}</label><div class="form-control-plaintext">{{ test.pascalCase ? test.pascalCase|date('Y-m-d H:i:s') : '-' }}</div></div>
            <div class="form-group"><label>{% trans %}label.camelCase{% endtrans %}</label><div class="form-control-plaintext">{% if test.camelCase %}{% trans from 'messages' %}label.yes{% endtrans %}{% else %}{% trans from 'messages' %}label.no{% endtrans %}{% endif %}</div></div>
            <div class="form-group"><label>{% trans %}label.snakeCase{% endtrans %}</label><div class="form-control-plaintext">{{ test.snakeCase|nl2br }}</div></div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ include('test/_delete_form.html.twig', {test: test}, with_context = false) }}
                </div>

                <div class="col text-right">
                    <a href="{{ path('tests.edit', {id: test.id}) }}" class="btn btn-outline-primary">
                        <i class="fa fa-edit" aria-hidden="true"></i>
                        <span class="btn-text">{% trans from 'messages' %}action.edit{% endtrans %}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderReadFkRelationsOk(): void
    {
        $render = new TemplateBuilder($this->getSchemaFkRelation(), 'read');

        $this->assertEquals(<<<T
{% trans_default_domain 'test' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}title.show{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}entity{% endtrans %}: {{ test.pk }}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                    <span class="btn-text">{% trans from 'messages' %}action.list{% endtrans %}</span>
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="form-group"><label>{% trans %}label.foo{% endtrans %}</label><div class="form-control-plaintext">{{ test.fooInstance.fuz|default('-') }}</div></div>
            <div class="form-group"><label>{% trans %}label.postId{% endtrans %}</label><div class="form-control-plaintext">{{ test.postIdInstance.name|default('-') }}</div></div>
            <div class="form-group"><label>{% trans %}label.statusId{% endtrans %}</label><div class="form-control-plaintext">{{ test.statusIdInstance.name|default('-') }}</div></div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ include('test/_delete_form.html.twig', {test: test}, with_context = false) }}
                </div>

                <div class="col text-right">
                    <a href="{{ path('tests.edit', {id: test.id}) }}" class="btn btn-outline-primary">
                        <i class="fa fa-edit" aria-hidden="true"></i>
                        <span class="btn-text">{% trans from 'messages' %}action.edit{% endtrans %}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new TemplateBuilder($this->getSchema(), 'update');

        $this->assertEquals(<<<T
{% trans_default_domain 'test' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}title.edit{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}title.edit{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                    <span class="btn-text">{% trans from 'messages' %}action.list{% endtrans %}</span>
                </a>
            </div>
        </div>

        {{ form_start(form, {'action': path('tests.update', {id: test.id}), 'method': 'PUT', 'attr': {'id': 'test'}}) }}
            <div class="card-body">
                {{ form_widget(form) }}
            </div>
        {{ form_end(form) }}

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ include('test/_delete_form.html.twig', {test: test}, with_context = false) }}
                </div>

                <div class="col text-right">
                    <button type="submit" form="test" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i>
                        <span class="btn-text">{% trans from 'messages' %}action.update{% endtrans %}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderUpdateFkRelationsOk(): void
    {
        $render = new TemplateBuilder($this->getSchemaFkRelation('PostComments'), 'update');

        $this->assertEquals(<<<T
{% trans_default_domain 'postComment' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}title.edit{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}title.edit{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('post-comments.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                    <span class="btn-text">{% trans from 'messages' %}action.list{% endtrans %}</span>
                </a>
            </div>
        </div>

        {{ form_start(form, {'action': path('post-comments.update', {id: postComment.id}), 'method': 'PUT', 'attr': {'id': 'postComment'}}) }}
            <div class="card-body">
                {{ form_widget(form) }}
            </div>
        {{ form_end(form) }}

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ include('postComment/_delete_form.html.twig', {postComment: postComment}, with_context = false) }}
                </div>

                <div class="col text-right">
                    <button type="submit" form="postComment" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i>
                        <span class="btn-text">{% trans from 'messages' %}action.update{% endtrans %}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
{% endblock %}

{% block stylesheets %}
    <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/select2/select2bs4.min.css') }}" />
{% endblock %}

{% block javascripts %}
    <script src="{{ asset('js/jquery/jquery.select2.min.js') }}"></script>
    <script src="{{ asset('js/postComments.js') }}"></script>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new TemplateBuilder($this->getSchema(), 'delete');

        $this->assertEquals(<<<T
{{ include('form/_delete_confirmation.html.twig') }}
<form id="delete-form" name="delete-form" method="post" action="{{ url('tests.delete', {id: test.id}) }}" data-confirmation="true">
    <input type="hidden" name="token" value="{{ csrf_token('delete') }}" />
    <input type="hidden" name="_method" value="delete" />
    <button type="submit" class="btn btn-outline-danger" tabindex="-1">
        <i class="fa fa-trash" aria-hidden="true"></i>
        <span class="btn-text">{% trans from 'messages' %}action.delete{% endtrans %}</span>
    </button>
</form>

T
, $render->build());
    }

    /**
     * @dataProvider getEntityName
     */
    public function testItOkWithDiffEntityName(string $name, string $expectedPath, string $expectedProperty): void
    {
        $render = new TemplateBuilder($this->getSchema($name), 'delete');

        $this->assertEquals(<<<T
{{ include('form/_delete_confirmation.html.twig') }}
<form id="delete-form" name="delete-form" method="post" action="{{ url('{$expectedPath}.delete', {id: {$expectedProperty}.id}) }}" data-confirmation="true">
    <input type="hidden" name="token" value="{{ csrf_token('delete') }}" />
    <input type="hidden" name="_method" value="delete" />
    <button type="submit" class="btn btn-outline-danger" tabindex="-1">
        <i class="fa fa-trash" aria-hidden="true"></i>
        <span class="btn-text">{% trans from 'messages' %}action.delete{% endtrans %}</span>
    </button>
</form>

T
, $render->build());
    }

    public function getEntityName(): array
    {
        return [
            ['userpassword', 'userpasswords', 'userpassword'],
            ['USERPASSWORD', 'userpasswords', 'userpassword'],
            ['UserPassword', 'user-passwords', 'userPassword'],
            ['userPassword', 'user-passwords', 'userPassword'],
            ['user_password', 'user-passwords', 'userPassword'],
            ['Posts', 'posts', 'post'],
        ];
    }
}
