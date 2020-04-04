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
    <h1>Posts List</h1>

    <table class="table table-striped table-middle-aligned">
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
{% endblock %}

{% block sidebar %}
    <div class="section actions">
        <a href="{{ path('posts.new') }}" class="btn btn-lg btn-block btn-success">
            <i class="fa fa-plus" aria-hidden="true"></i> Create
        </a>
    </div>

    {{ parent() }}
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
    <h1>New Post</h1>
    <form name="form" method="post">
        <div class="form-group"><label for="form_title">Title</label><input type="text" id="form_title" name="form[title]" class="form-control" /></div>
        <div class="form-group"><label for="form_content">Content</label><input type="text" id="form_content" name="form[content]" class="form-control" /></div>
        <div class="form-group"><label for="form_createdAt">Created at</label><input type="text" id="form_createdAt" name="form[createdAt]" class="form-control" /></div>

        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save" aria-hidden="true"></i> Save
        </button>

        <a href="{{ path('posts.index') }}" class="btn btn-link">
            <i class="fa fa-list-alt" aria-hidden="true"></i> Show list
        </a>
    </form>
{% endblock %}

T
, $render->build());
    }

    public function _testItRenderReadOk(): void
    {
    }

    public function _testItRenderUpdateOk(): void
    {
    }

    public function _testItRenderDeleteOk(): void
    {
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
