<?php

namespace Tests\Feature\Http\Web;

use Tests\TestCase;

final class MainApplicationUrlTest extends TestCase
{
    public function test_the_application_returns_a_method_not_allowed_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(405);
    }
}
