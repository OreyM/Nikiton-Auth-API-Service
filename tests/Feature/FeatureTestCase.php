<?php

namespace Tests\Feature;

use Database\Seeders\TestDatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;
use Laravel\Passport\Client;

abstract class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createPassportClient(
            name: 'Test Personal Access Client',
            provider: 'users',
            redirectUris: [],
            grantTypes: ['personal_access']
        );
    }

    public function seed($class = 'Database\\Seeders\\DatabaseSeeder'): void
    {
        $this->artisan('db:seed', ['--class' => TestDatabaseSeeder::class]);
    }

    private function createPassportClient(
        string $name,
        string $provider,
        array $redirectUris,
        array $grantTypes
    ): void
    {
        Client::create([
            'name'          => $name,
            'secret'        => Str::random(40),
            'provider'      => $provider,
            'redirect_uris' => $redirectUris,
            'grant_types'   => $grantTypes,
            'revoked'       => false,
        ]);
    }
}
