<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Field;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

class FieldBuilderTest extends TestCase
{
    /** @test */
    function it_generates_a_text_field()
    {
        $this->assertTemplateMatches(
            'field/text', Field::text('name', 'value')
        );
    }

    /** @test */
    function it_generates_a_required_text_field()
    {
        $this->assertTemplateMatches(
            'field/text-required', Field::text('name', ['required' => true])
        );
    }

    /** @test */
    function it_generates_a_required_password_field()
    {
        $this->assertTemplateMatches(
            'field/password-required', Field::password('password')->required()
        );
    }

    /** @test */
    public function it_generates_a_text_field_with_a_custom_label()
    {
        $this->assertTemplateMatches(
            'field/text-custom-label', Field::text('name', 'value', ['label' => 'Full name'])
        );
    }

    /** @test */
    public function it_generates_a_select_field()
    {
         trans()->addLines([
             'validation.empty_option.default' => 'Select value',
         ], 'en');

        $this->assertTemplateMatches(
            'field/select', Field::select('gender', ['m' => 'Male', 'f' => 'Female'])
        );
    }

    /** @test */
    function it_adds_an_empty_option_to_select_fields()
    {
        $this->assertTemplateMatches(
            'field/select-empty', Field::select('gender', ['m' => 'Male', 'f' => 'Female'], ['empty' => 'Select gender'])
        );
    }

    /** @test */
    function it_generates_a_multiple_select_field()
    {
        $options = [
            'php'     => 'PHP',
            'laravel' => 'Laravel',
            'symfony' => 'Symfony',
            'ruby'    => 'Ruby on Rails'
        ];

        $this->assertTemplateMatches(
            'field/select-multiple', Field::select('tags', $options, ['php', 'laravel'], ['multiple'])
        );

        $this->assertTemplateMatches(
            'field/select-multiple', Field::selectMultiple('tags', $options, ['php', 'laravel'])
        );
    }

    /** @test */
    function it_generates_a_multiple_select_field_with_optgroup()
    {
        $options = [
            'backend' => [
                'laravel' => 'Laravel',
                'rails' => 'Ruby on Rails',
            ],
            'frontend' => [
                'vue' => 'Vue',
                'angular' => 'Angular',
            ],
        ];

        $this->assertTemplateMatches(
            'field/select-group', Field::selectMultiple('frameworks', $options, ['vue', 'laravel'])
        );
    }

    /** @test */
    function it_generates_a_text_field_with_errors()
    {
        tap(app('session.store'), function ($session) {
            $session->put('errors', new MessageBag([
                'name' => ['This is really wrong']
            ]));

            Field::setSessionStore($session);
        });

        $this->assertTemplateMatches(
            'field/text_with_errors', Field::text('name')
        );
    }

    /** @test */
    function it_generates_checkboxes()
    {
        $tags = [
            'php' => 'PHP',
            'python' => 'Python',
            'js' => 'JS',
            'ruby' => 'Ruby on Rails'
        ];
        $checked = ['php', 'js'];

        $this->assertTemplateMatches(
            'field/checkboxes', Field::checkboxes('tags', $tags, $checked)
        );
    }

    /** @test */
    function it_generate_radios()
    {
        $this->assertTemplateMatches(
            'field/radios', Field::radios('gender', ['m' => 'Male', 'f' => 'Female'], 'm')
        );
    }

    /** @test */
    function it_not_render_if_not_pass_ifis_method()
    {
        $field = Field::text('name')->required()->ifIs('foo-bar');

        $this->assertSame(null, $field->render());
    }

    /** @test */
    function it_not_render_if_not_pass_ifguest_method()
    {
        $this->actingAs($this->getUser());

        $field = Field::text('name')->required()->ifGuest();

        $this->assertSame(null, $field->render());
    }

    /** @test */
    function it_not_render_if_not_pass_ifauth_method()
    {
        $field = Field::text('name')->required()->ifAuth();

        $this->assertSame(null, $field->render());
    }

    /** @test */
    function it_not_render_if_not_pass_ifcan_method()
    {
        $this->actingAs($this->getUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        $field = Field::text('name')->required()->ifCan('edit-all');

        $this->assertSame(null, $field->render());
    } 

    /** @test */
    function it_not_render_if_not_pass_ifcannot_method()
    {
        $this->actingAs($this->getUser());

        Gate::define('edit-all', function ($user) {
            return true;
        });

        $field = Field::text('name')->required()->ifCannot('edit-all');

        $this->assertSame(null, $field->render()); 
    }
  
    function can_customize_the_template()
    {
        View::addLocation(__DIR__.'/views');

        $field = Field::text('name', 'value')->template('custom-templates.field-text');

        $this->assertInstanceOf(\Styde\Html\FormModel\Field::class, $field);
        $this->assertTemplateMatches('field/text-custom-template', $field);
    }
}