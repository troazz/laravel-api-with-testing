<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogin()
    {
        $response = $this->_getToken();
        $response
            ->assertStatus(200)
            ->assertSee('access_token');
    }

    public function testProfile()
    {
        $response = $this->_getToken();
        $json     = $response->decodeResponseJson();

        $response = $this->withHeaders([
            'X-Header'      => 'XMLHttpRequest',
            'Authorization' => 'Bearer ' . $json['access_token'],
        ])->json('GET', '/api/auth/me');

        $response
            ->assertStatus(200)
            ->assertJson([
                'email' => $json['user']['email'],
            ]);
    }

    public function testLogout()
    {
        $response = $this->_getToken();
        $json     = $response->decodeResponseJson();

        $response = $this->withHeaders([
            'X-Header'      => 'XMLHttpRequest',
            'Authorization' => 'Bearer ' . $json['access_token'],
        ])->json('POST', '/api/auth/logout');

        $response
            ->assertStatus(200)
            ->assertExactJson(['message' => 'Successfully logged out.']);
    }

    public function testLoginInvalidParams()
    {
        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('POST', '/api/auth/login', ['email' => '', 'password' => '']);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Parameters validation error.',
                'errors'  => true,
            ]);
    }

    public function testLoginWrongPassword()
    {
        $user = factory(User::class)->create([
            'email'    => 'username@example.net',
            'password' => bcrypt('secret'),
        ]);

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('POST', '/api/auth/login', ['email' => $user->email, 'password' => 'password']);

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Email or password doesn\'t match.',
            ]);
    }

    public function testProfileWithNoBearer()
    {
        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('GET', '/api/auth/me');

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    private function _getToken()
    {
        $user = factory(User::class)->create([
            'email'    => 'username@example.net',
            'password' => bcrypt('secret'),
        ]);

        $response = $this->withHeaders([
            'X-Header' => 'XMLHttpRequest',
        ])->json('POST', '/api/auth/login', ['email' => $user->email, 'password' => 'secret']);

        return $response;
    }
}
