<?php

namespace Tests\Feature;

use App\Entities\Blogger;
use App\Enums\BloggerRole;
use Illuminate\Support\Facades\Hash;
use LaravelDoctrine\ORM\Testing\Factory;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = $this->makeFactory();
        $this->artisan('app:truncate-database');
    }

    public function test_register(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'User successfully registered',
                'status_code' => Response::HTTP_OK,
                'data' => [
                    'user' => [
                        'name' => 'John Doe',
                        'email' => 'john.doe@example.com',
                    ],
                ],
                'errors' => [],
            ]);
    }

    public function test_login(): void
    {
        /** @var Blogger $blogger */
        $blogger = $this->factory->of(Blogger::class)->create();

        $credentials = [
            'email' => $blogger->getEmail(),
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $credentials);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);

        $response->assertJsonFragment([
            'token_type' => 'Bearer',
        ]);
        /** @var string $token */
        $token = $response->json('access_token');
        $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/me')
            ->assertStatus(Response::HTTP_OK);

    }

    public function testLogoutSuccessfully(): void
    {
        $blogger = new Blogger(
            'Test User',
            'test@example.com',
            Hash::make('password123'),
            BloggerRole::BLOGGER
        );
        $this->entityManager->persist($blogger);
        $this->entityManager->flush();

        $token = JWTAuth::fromUser($blogger);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/logout');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Successfully logged out',
            ]);

        $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/me')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testLogoutWithoutToken(): void
    {
        $response = $this->postJson('/api/logout');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
