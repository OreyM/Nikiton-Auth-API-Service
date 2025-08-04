<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Tests\Unit\Domain\Auth\Service;

use App\Domain\Auth\Service\AuthService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_it_returns_true_if_the_passwords_match(): void
    {
        $service = new AuthService();
        $password = 'password';
        $actualHashedPassword = Hash::make($password);

        $isPasswordMatch = $service->comparePasswords($password, $actualHashedPassword);

        $this->assertTrue($isPasswordMatch);
    }

    public function test_it_returns_false_if_the_passwords_do_not_match(): void
    {
        $service = new AuthService();
        $actualHashedPassword = Hash::make('password');
        $wrongPassword = 'wrong_password';

        $isPasswordMatch = $service->comparePasswords($wrongPassword, $actualHashedPassword);

        $this->assertFalse($isPasswordMatch);
    }
}
