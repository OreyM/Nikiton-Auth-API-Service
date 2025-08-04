<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Domain\Auth\Service;

final class AuthService
{
    public function comparePasswords(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
