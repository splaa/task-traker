<?php

namespace Tests\Feature;

use Illuminate\Auth\DatabaseUserProvider;
use Illuminate\Auth\TokenGuard;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('mysql')]
class MySqlTokenGuardSecurityTest extends TestCase
{
    public function test_mysql_numeric_coercion_cannot_bypass_token_guard(): void
    {
        $db = $this->app['db']->connection();
        if ($db->getDriverName() !== 'mysql') {
            $this->markTestSkipped('Requires the real MySQL provider; run by the MySQL CI jobs.');
        }

        $db->statement('CREATE TEMPORARY TABLE token_guard_security_probe (id INT PRIMARY KEY, api_token VARCHAR(80), password VARCHAR(80))');
        try {
            $db->table('token_guard_security_probe')->insert([
                'id' => 1, 'api_token' => '1-secret', 'password' => 'unused',
            ]);
            $provider = new DatabaseUserProvider($db, $this->app['hash'], 'token_guard_security_probe');

            // GHSA-f9x3-7vq9-cph6: prove MySQL would coerce this integer into a match.
            $this->assertNotNull($provider->retrieveByCredentials(['api_token' => 1]));
            foreach ([1, true, ['1-secret']] as $token) {
                $guard = new TokenGuard($provider, Request::create('/', 'POST', ['api_token' => $token]));
                $this->assertNull($guard->user());
                $this->assertFalse($guard->validate(['api_token' => $token]));
            }

            $guard = new TokenGuard($provider, Request::create('/', 'POST', ['api_token' => '1-secret']));
            $this->assertNotNull($guard->user());
            $this->assertTrue($guard->validate(['api_token' => '1-secret']));
        } finally {
            $db->statement('DROP TEMPORARY TABLE token_guard_security_probe');
        }
    }
}
