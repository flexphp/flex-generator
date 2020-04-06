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
        $render = new TemplateBuilder('Posts', 'index', $this->getProperties());

        $this->assertEquals(<<<T
{% extends 'form/layout.html.twig' %}

{% block title 'Posts' %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">Posts</h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('posts.new') }}" class="btn btn-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> Create
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
                        <td class="text-right">
                            <div class="item-actions">
                                <a href="{{ path('posts.read', {id: register.id}) }}" class="btn btn-sm btn-default">
                                    <i class="fa fa-eye" aria-hidden="true"></i> Show
                                </a>

                                <a href="{{ path('posts.edit', {id: register.id}) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Edit
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
        $render = new TemplateBuilder('Posts', 'create', $this->getProperties());

        $this->assertEquals(<<<T
{% extends 'form/layout.html.twig' %}

{% block title 'Posts - New' %}

{% block main %}
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-header-title">New Post</h3>
            <div class="toolbar ml-auto">
                <a href="{{ path('posts.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> Show list
                </a>
            </div>
        </div>

        <form id="new-form" name="new-form" method="post" action="{{ path('posts.create') }}">
            <div class="card-body">
                <div class="form-group"><label for="form_title">Title</label><input type="text" id="form_title" name="form[title]" class="form-control" /></div>
                <div class="form-group"><label for="form_content">Content</label><input type="text" id="form_content" name="form[content]" class="form-control" /></div>
                <div class="form-group"><label for="form_createdAt">Created at</label><input type="text" id="form_createdAt" name="form[createdAt]" class="form-control" /></div>
            </div>

            <div class="card-footer">
                <a class="btn btn-outline-default" onclick="history.back()" href="javascript:;">
                    <i class="fa fa-back" aria-hidden="true"></i> Back
                </a>

                <button type="submit" class="btn btn-primary float-right">
                    <i class="fa fa-save" aria-hidden="true"></i> Save
                </button>
            </div>
        </form>
    </div>
{% endblock %}

T
, $render->build());
    }

    public function testItRenderReadOk(): void
    {
        $render = new TemplateBuilder('Posts', 'read', $this->getProperties());

        $this->assertEquals(<<<T
{% extends 'form/layout.html.twig' %}

{% block title 'Posts Detail' %}

{% block main %}
    <h1>Posts</h1>

    <p class="metadata">
        <span class="metadata">{{ register.title }}</span>
        <span class="metadata">{{ register.content }}</span>
        <span class="metadata">{{ register.createdAt }}</span>
    </p>
{% endblock %}

{% block sidebar %}
    <div class="section">
        <a href="{{ path('posts.edit', {id: register.id}) }}" class="btn btn-lg btn-block btn-success">
            <i class="fa fa-edit" aria-hidden="true"></i> Edit
        </a>
    </div>

    <div class="section">
        {{ include('post/_delete_form.html.twig', {post: register}, with_context = false) }}
    </div>

    {{ parent() }}
{% endblock %}

T
, $render->build());
    }

    public function testItRenderUpdateOk(): void
    {
        $render = new TemplateBuilder('Posts', 'update', $this->getProperties());

        $this->assertEquals(<<<T
{% extends 'form/layout.html.twig' %}

{% block title 'Posts - Edit' %}

{% block main %}
    <h1>Post {{ post.id }}</h1>

    <form id="edit-form" name="edit-form" method="post" action="{{ path('posts.update', {id: post.id}) }}">
        <div class="form-group"><label for="form_title">Title</label><input type="text" id="form_title" name="form[title]" class="form-control" /></div>
        <div class="form-group"><label for="form_content">Content</label><input type="text" id="form_content" name="form[content]" class="form-control" /></div>
        <div class="form-group"><label for="form_createdAt">Created at</label><input type="text" id="form_createdAt" name="form[createdAt]" class="form-control" /></div>

        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save" aria-hidden="true"></i> Update
        </button>

        <a href="{{ path('posts.index') }}" class="btn btn-link">
            <i class="fa fa-list-alt" aria-hidden="true"></i> Show list
        </a>
    </form>
{% endblock %}

{% block sidebar %}
    <div class="section">
        <a href="{{ path('posts.read', {id: post.id}) }}" class="btn btn-lg btn-block btn-success">
            <i class="fa fa-eye" aria-hidden="true"></i> Show
        </a>
    </div>

    <div class="section actions">
        {{ include('post/_delete_form.html.twig', {post: post}, with_context = false) }}
    </div>

    {{ parent() }}
{% endblock %}

T
, $render->build());
    }

    public function testItRenderDeleteOk(): void
    {
        $render = new TemplateBuilder('Posts', 'delete', $this->getProperties());

        $this->assertEquals(<<<T
{{ include('form/_delete_confirmation.html.twig') }}
<form id="delete-form" name="delete-form" method="post" action="{{ url('posts.delete', {id: post.id}) }}" data-confirmation="true">
    <input type="hidden" name="token" value="{{ csrf_token('delete') }}" />
    <button type="submit" class="btn btn-lg btn-block btn-danger">
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
                'Name' => 'title',
                'DataType' => 'string',
            ],
            [
                'Name' => 'content',
                'DataType' => 'text',
            ],
            [
                'Name' => 'createdAt',
                'DataType' => 'datetime',
            ],
        ];
    }
}