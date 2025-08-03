<?php

namespace Tests\Feature\Http\Web;

use Tests\TestCase;

class WebAuthLoginTest extends TestCase
{
    public function test_web_auth_login_returns_a_method_not_allowed_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(405);
    }
}
