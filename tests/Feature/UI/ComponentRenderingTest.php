<?php

namespace Tests\Feature\UI;

use Tests\TestCase;

class ComponentRenderingTest extends TestCase
{
    public function test_ui_playground_renders_successfully(): void
    {
        $response = $this->get('/ui-playground');

        $response->assertStatus(200);
        $response->assertSee('Design System & UI Library', false);
        
        // Check for specific components being rendered
        $response->assertSee('<button', false);
        $response->assertSee('<input', false);
        $response->assertSee('<textarea', false);
        $response->assertSee('<select', false);
        $response->assertSee('role="alert"', false); // Alert component
    }
}
