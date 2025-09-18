<?php

namespace Database\Seeders;

use Database\Seeders\Users\UserDefaultSeed;
use Illuminate\Database\Seeder;

/**
 * Call DB seeders only for feature tests
 */
class TestDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserDefaultSeed::class);
    }
}
