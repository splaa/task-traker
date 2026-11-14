<?php

namespace Tests\Unit;

use Illuminate\Auth\GenericUser;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TokenGuardSecurityTest extends TestCase
{
    public static function invalidTokens(): array
    {
        return [[true], [1], [1.5], [['token']], [null], ['']];
    }

    #[DataProvider('invalidTokens')]
    public function test_non_string_tokens_never_reach_the_user_provider(mixed $token): void
    {
        // GHSA-f9x3-7vq9-cph6: MySQL can coerce a non-string token into a match.
        $provider = $this->createMock(UserProvider::class);
        $provider->expects($this->never())->method('retrieveByCredentials');
        $guard = new TokenGuard($provider, Request::create('/', 'POST', ['api_token' => $token]));

        $this->assertNull($guard->user());
        $this->assertFalse($guard->validate(['api_token' => $token]));
    }

    public function test_valid_string_token_still_authenticates(): void
    {
        $user = new GenericUser(['id' => 1]);
        $provider = $this->createMock(UserProvider::class);
        $provider->expects($this->exactly(2))->method('retrieveByCredentials')
            ->with(['api_token' => 'valid-secret'])->willReturn($user);
        $guard = new TokenGuard($provider, Request::create('/', 'POST', ['api_token' => 'valid-secret']));

        $this->assertSame($user, $guard->user());
        $this->assertTrue($guard->validate(['api_token' => 'valid-secret']));
    }
}
