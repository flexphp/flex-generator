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
use FlexPHP\Schema\Constants\Keyword;

final class TemplateBuilderTest extends TestCase
{
    public function testItRenderIndexOk(): void
    {
        $render = new TemplateBuilder('Tests', 'index', $this->getProperties());

        $this->assertEquals(<<<T
{% extends 'form/layout.html.twig' %}

{% block title 'Tests' %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">Tests</h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.new') }}" class="btn btn-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> New
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Content</th>
                        <th scope="col">Created At</th>
                        <th scope="col" class="text-center"><i class="fa fa-cogs" aria-hidden="true"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                {% for register in registers %}
                    <tr>
                        <td>{{ register.title }}</td>
                        <td>{{ register.content }}</td>
                        <td>{{ register.createdAt }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ path('tests.read', {id: register.id}) }}" class="btn btn-sm btn-outline-light">
                                    <i class="fa fa-eye text-dark" aria-hidden="true"></i>
                                </a>

                                <a href="{{ path('tests.edit', {id: register.id}) }}" class="btn btn-sm btn-outline-light">
                                    <i class="fa fa-edit text-primary" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                {% else %}
                    <tr>
                        <td colspan="4" align="center">Nothing here</td>
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

    public function testItRenderCreateOk(): void
    {
        $render = new TemplateBuilder('Tests', 'create', $this->getProperties());

        $this->assertEquals(<<<T
{% extends 'form/layout.html.twig' %}

{% block title 'Tests - New' %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">New Test</h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> List
                </a>
            </div>
        </div>

        {{ form_start(form, {'action': path('tests.create')}) }}
            <div class="card-body">
                {{ form_widget(form) }}
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Create
                </button>
            </div>
        {{ form_end(form) }}
    </div>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new TemplateBuilder('Tests', 'read', $this->getProperties());

        $this->assertEquals(<<<T
{% extends 'form/layout.html.twig' %}

{% block title 'Tests Detail' %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">Test</h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> List
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="form-group"><label>Title</label><div class="form-control-plaintext">{{ register.title }}</div></div>
            <div class="form-group"><label>Content</label><div class="form-control-plaintext">{{ register.content }}</div></div>
            <div class="form-group"><label>Created At</label><div class="form-control-plaintext">{{ register.createdAt }}</div></div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ include('test/_delete_form.html.twig', {test: register}, with_context = false) }}
                </div>

                <div class="col text-right">
                    <a href="{{ path('tests.edit', {id: register.id}) }}" class="btn btn-outline-primary">
                        <i class="fa fa-edit" aria-hidden="true"></i> Edit
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
        $render = new TemplateBuilder('Tests', 'update', $this->getProperties());

        $this->assertEquals(<<<T
{% extends 'form/layout.html.twig' %}

{% block title 'Tests - Edit' %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">Edit Test</h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('tests.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> List
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
                        <i class="fa fa-save" aria-hidden="true"></i> Update
                    </button>
                </div>
            </div>
        </div>
    </div>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new TemplateBuilder('Tests', 'delete', $this->getProperties());

        $this->assertEquals(<<<T
{{ include('form/_delete_confirmation.html.twig') }}
<form id="delete-form" name="delete-form" method="post" action="{{ url('tests.delete', {id: test.id}) }}" data-confirmation="true">
    <input type="hidden" name="token" value="{{ csrf_token('delete') }}" />
    <input type="hidden" name="_method" value="delete" />
    <button type="submit" class="btn btn-outline-danger" tabindex="-1">
        <i class="fa fa-trash" aria-hidden="true"></i> Delete
    </button>
</form>

T
, $render->build());
    }

    private function getProperties(): array
    {
        return [
            [
                Keyword::NAME => 'title',
                Keyword::DATATYPE => 'string',
            ],
            [
                Keyword::NAME => 'content',
                Keyword::DATATYPE => 'text',
            ],
            [
                Keyword::NAME => 'createdAt',
                Keyword::DATATYPE => 'datetime',
            ],
        ];
    }
}
