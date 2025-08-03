<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Domain\User\Queries;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Models\User;

class GetUserByEmailQuery
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $email
     *
     * @return \App\Models\User|null
     */
    public function handle(string $email):? User
    {
        return $this->user
            ->where('email', $email)
            ->first();
    }
}
