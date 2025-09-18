<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Database\Seeders\Users;

use App\Models\User;
use Illuminate\Database\Seeder;
use JetBrains\PhpStorm\NoReturn;

class UserDefaultSeed extends Seeder
{
    private User $userModel;
    private string $login;
    private string $password;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;

        $this->login = config('auth.user.default.login');
        $this->password = config('auth.user.default.password');
    }

    public function run(): void
    {
        if (!$this->login) {
            throw new \Exception('env values USER_DEFAULT_LOGIN not set');
        }

        if (!$this->password) {
            throw new \Exception('env values USER_DEFAULT_PASSWORD not set');
        }

        $this->userModel->create([
            'name'      => 'Default test user',
            'email'     => $this->login,
            'password'  => $this->password,
        ]);
    }
}
