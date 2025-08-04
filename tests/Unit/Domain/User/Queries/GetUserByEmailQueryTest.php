<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Tests\Unit\Domain\User\Queries;

use App\Domain\User\Queries\GetUserByEmailQuery;
use App\Models\User;
use Mockery;
use Tests\TestCase;

final class GetUserByEmailQueryTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_returns_user_when_found(): void
    {
        $email = 'test_user@mail.com';
        $mockFoundUser = Mockery::mock(User::class);

        $mockUserModel = Mockery::mock(User::class);
        $mockUserModel->shouldReceive('where')
            ->with('email', $email)
            ->andReturnSelf();
        $mockUserModel->shouldReceive('first')
            ->andReturn($mockFoundUser);

        $query = new GetUserByEmailQuery($mockUserModel);
        $result = $query->handle($email);

        $this->assertEquals($mockFoundUser, $result);
    }

    public function test_it_returns_null_when_user_not_found(): void
    {
        $email = 'test_user@mail.com';

        $mockUserModel = Mockery::mock(User::class);
        $mockUserModel->shouldReceive('where')
            ->with('email', $email)
            ->andReturnSelf();
        $mockUserModel->shouldReceive('first')
            ->andReturn(null);

        $query = new GetUserByEmailQuery($mockUserModel);
        $result = $query->handle($email);

        $this->assertNull($result);
    }
}
