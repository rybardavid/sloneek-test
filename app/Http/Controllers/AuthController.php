<?php

namespace App\Http\Controllers;

use App\Entities\Blogger;
use App\Enums\BloggerRole;
use App\Http\Requests\AuthRegisterRequest;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Login and return JWT.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid email or password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $user = new Blogger(
            $request->name,
            $request->email,
            Hash::make($request->password),
            BloggerRole::BLOGGER,
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->successResponse([
            'user' => [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ],
        ], 'User successfully registered');
    }

    public function me(): JsonResponse
    {
        return response()->json(JWTAuth::user());
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }
}
