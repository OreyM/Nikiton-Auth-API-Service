<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace Tests\Unit\Domain\Auth\Service;

use App\Data\Types\TokenType;
use App\Domain\Auth\Entity\TokenEntity;
use App\Domain\Auth\Service\BearerTokenService;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use Mockery;
use ReflectionClass;
use Tests\TestCase;

final class BearerTokenServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_generates_token_successfully(): void
    {
        $expiresAt = Carbon::parse('2029-07-05 12:00:00');
        $accessToken = 'fake_access_token';

        $mockToken = Mockery::mock(Token::class);
        $mockToken->shouldReceive('getAttribute')
            ->with('expires_at')
            ->andReturn($expiresAt->toDateTimeString());

        $tokenResult = new PersonalAccessTokenResult();
        $tokenResult->accessToken = $accessToken;

        $ref = new ReflectionClass(PersonalAccessTokenResult::class);
        $prop = $ref->getProperty('token');
        $prop->setAccessible(true);
        $prop->setValue($tokenResult, $mockToken);

        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('createToken')
            ->with('API')
            ->andReturn($tokenResult);

        $service = new BearerTokenService();
        $tokenEntity = $service->setAuthUser($mockUser)
            ->generateToken();

        $this->assertInstanceOf(TokenEntity::class, $tokenEntity);
        $this->assertEquals(TokenType::BEARER, $tokenEntity->type);
        $this->assertEquals($accessToken, $tokenEntity->value);
        $this->assertTrue($expiresAt->equalTo($tokenEntity->expiresAt));
    }

    public function test_it_throws_exception_when_user_not_set(): void
    {
        $this->expectException(UserNotFoundException::class);

        $service = new BearerTokenService();
        $service->generateToken();
    }

    public function test_get_auth_user(): void
    {
        $mockUser = Mockery::mock(User::class);

        $service = new BearerTokenService();
        $service->setAuthUser($mockUser);

        $this->assertInstanceOf(User::class, $service->getAuthUser());
    }

    public function test_set_token_name(): void
    {
        $service = new BearerTokenService();
        $tokenName = 'Test API token name';

        $service->setTokenName($tokenName);

        $this->assertEquals($tokenName, $service->getTokenName());
    }
}
