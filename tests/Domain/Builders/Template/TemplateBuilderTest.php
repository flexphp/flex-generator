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

{% block title %}{% trans %}entity{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}entity{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.new') }}" class="btn btn-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> {% trans from 'messages' %}action.new{% endtrans %}
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">{% trans %}label.lower{% endtrans %}</th>
                        <th scope="col">{% trans %}label.upper{% endtrans %}</th>
                        <th scope="col">{% trans %}label.pascalCase{% endtrans %}</th>
                        <th scope="col">{% trans %}label.camelCase{% endtrans %}</th>
                        <th scope="col">{% trans %}label.snakeCase{% endtrans %}</th>
                        <th scope="col" class="text-center"><i class="fa fa-cogs" aria-hidden="true"></i> {% trans from 'messages' %}action.options{% endtrans %}</th>
                    </tr>
                </thead>
                <tbody>
                {% for register in registers %}
                    <tr>
                        <td>{{ register.lower }}</td>
                        <td>{{ register.upper }}</td>
                        <td>{{ register.pascalCase ? register.pascalCase|date('Y-m-d H:i:s') : '-' }}</td>
                        <td>{% if register.camelCase %}{% trans from 'messages' %}label.yes{% endtrans %}{% else %}{% trans from 'messages' %}label.no{% endtrans %}{% endif %}</td>
                        <td>{{ register.snakeCase }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ path('tests.read', {id: register.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.read{% endtrans %}">
                                    <i class="fa fa-eye text-dark" aria-hidden="true"></i>
                                </a>

                                <a href="{{ path('tests.edit', {id: register.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.edit{% endtrans %}">
                                    <i class="fa fa-edit text-primary" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                {% else %}
                    <tr>
                        <td colspan="6" align="center">Nothing here</td>
                    </tr>
                {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderIndexBlameAtOk(): void
    {
        $render = new TemplateBuilder($this->getSchemaAiAndBlameAt(), 'index');

        $this->assertEquals(<<<T
{% trans_default_domain 'test' %}
{% extends 'form/layout.html.twig' %}

{% block title %}{% trans %}entity{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}entity{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.new') }}" class="btn btn-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> {% trans from 'messages' %}action.new{% endtrans %}
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">{% trans %}label.key{% endtrans %}</th>
                        <th scope="col">{% trans %}label.value{% endtrans %}</th>
                        <th scope="col" class="text-center"><i class="fa fa-cogs" aria-hidden="true"></i> {% trans from 'messages' %}action.options{% endtrans %}</th>
                    </tr>
                </thead>
                <tbody>
                {% for register in registers %}
                    <tr>
                        <td>{{ register.key }}</td>
                        <td>{{ register.value }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ path('tests.read', {id: register.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.read{% endtrans %}">
                                    <i class="fa fa-eye text-dark" aria-hidden="true"></i>
                                </a>

                                <a href="{{ path('tests.edit', {id: register.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.edit{% endtrans %}">
                                    <i class="fa fa-edit text-primary" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                {% else %}
                    <tr>
                        <td colspan="3" align="center">Nothing here</td>
                    </tr>
                {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
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

{% block title %}{% trans %}entity{% endtrans %}{% endblock %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">
                {% trans %}entity{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.new') }}" class="btn btn-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> {% trans from 'messages' %}action.new{% endtrans %}
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">{% trans %}label.code{% endtrans %}</th>
                        <th scope="col">{% trans %}label.name{% endtrans %}</th>
                        <th scope="col" class="text-center"><i class="fa fa-cogs" aria-hidden="true"></i> {% trans from 'messages' %}action.options{% endtrans %}</th>
                    </tr>
                </thead>
                <tbody>
                {% for register in registers %}
                    <tr>
                        <td>{{ register.code }}</td>
                        <td>{{ register.name }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ path('tests.read', {id: register.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.read{% endtrans %}">
                                    <i class="fa fa-eye text-dark" aria-hidden="true"></i>
                                </a>

                                <a href="{{ path('tests.edit', {id: register.id}) }}" class="btn btn-sm btn-outline-light" title="{% trans from 'messages' %}action.edit{% endtrans %}">
                                    <i class="fa fa-edit text-primary" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                {% else %}
                    <tr>
                        <td colspan="3" align="center">Nothing here</td>
                    </tr>
                {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
{% endblock %}

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
                    <i class="fa fa-list-alt" aria-hidden="true"></i> {% trans from 'messages' %}action.list{% endtrans %}
                </a>
            </div>
        </div>

        {{ form_start(form, {'action': path('tests.create')}) }}
            <div class="card-body">
                {{ form_widget(form) }}
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> {% trans from 'messages' %}action.create{% endtrans %}
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
                    <i class="fa fa-list-alt" aria-hidden="true"></i> {% trans from 'messages' %}action.list{% endtrans %}
                </a>
            </div>
        </div>

        {{ form_start(form, {'action': path('post-comments.create')}) }}
            <div class="card-body">
                {{ form_widget(form) }}
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> {% trans from 'messages' %}action.create{% endtrans %}
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
                {% trans %}entity{% endtrans %}
            </h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> {% trans from 'messages' %}action.list{% endtrans %}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="form-group"><label>{% trans %}label.lower{% endtrans %}</label><div class="form-control-plaintext">{{ register.lower }}</div></div>
            <div class="form-group"><label>{% trans %}label.upper{% endtrans %}</label><div class="form-control-plaintext">{{ register.upper }}</div></div>
            <div class="form-group"><label>{% trans %}label.pascalCase{% endtrans %}</label><div class="form-control-plaintext">{{ register.pascalCase ? register.pascalCase|date('Y-m-d H:i:s') : '-' }}</div></div>
            <div class="form-group"><label>{% trans %}label.camelCase{% endtrans %}</label><div class="form-control-plaintext">{% if register.camelCase %}{% trans from 'messages' %}label.yes{% endtrans %}{% else %}{% trans from 'messages' %}label.no{% endtrans %}{% endif %}</div></div>
            <div class="form-group"><label>{% trans %}label.snakeCase{% endtrans %}</label><div class="form-control-plaintext">{{ register.snakeCase|nl2br }}</div></div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ include('test/_delete_form.html.twig', {test: register}, with_context = false) }}
                </div>

                <div class="col text-right">
                    <a href="{{ path('tests.edit', {id: register.id}) }}" class="btn btn-outline-primary">
                        <i class="fa fa-edit" aria-hidden="true"></i> {% trans from 'messages' %}action.edit{% endtrans %}
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
                    <i class="fa fa-list-alt" aria-hidden="true"></i> {% trans from 'messages' %}action.list{% endtrans %}
                </a>
            </div>
        </div>

        {{ form_start(form, {'action': path('tests.update', {id: register.id}), 'method': 'PUT', 'attr': {'id': 'test'}}) }}
            <div class="card-body">
                {{ form_widget(form) }}
            </div>
        {{ form_end(form) }}

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ include('test/_delete_form.html.twig', {test: register}, with_context = false) }}
                </div>

                <div class="col text-right">
                    <button type="submit" form="test" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> {% trans from 'messages' %}action.update{% endtrans %}
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
                    <i class="fa fa-list-alt" aria-hidden="true"></i> {% trans from 'messages' %}action.list{% endtrans %}
                </a>
            </div>
        </div>

        {{ form_start(form, {'action': path('post-comments.update', {id: register.id}), 'method': 'PUT', 'attr': {'id': 'postComment'}}) }}
            <div class="card-body">
                {{ form_widget(form) }}
            </div>
        {{ form_end(form) }}

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ include('postComment/_delete_form.html.twig', {postComment: register}, with_context = false) }}
                </div>

                <div class="col text-right">
                    <button type="submit" form="postComment" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> {% trans from 'messages' %}action.update{% endtrans %}
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
        <i class="fa fa-trash" aria-hidden="true"></i> {% trans from 'messages' %}action.delete{% endtrans %}
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
        <i class="fa fa-trash" aria-hidden="true"></i> {% trans from 'messages' %}action.delete{% endtrans %}
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
