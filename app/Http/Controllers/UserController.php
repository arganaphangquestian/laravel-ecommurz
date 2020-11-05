<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"User"},
     *     summary="Returns a Token for Authenticated User",
     *     description="User login operation",
     *     operationId="userLogin",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User Authentication",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *         ),
     *     ),
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"User"},
     *     summary="Returns a Token for Authenticated User",
     *     description="User register operation.",
     *     operationId="userRegister",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User Authentication",
     *         @OA\JsonContent(
     *             required={"name", "email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@mail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="PassWord12345"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="number", example="321"),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", format="email", type="string", example="johndoe@mail.com"),
     *                 @OA\Property(property="updated_at", type="string"),
     *                 @OA\Property(property="created_at", type="string"),
     *             ),
     *             @OA\Property(property="token", type="string"),
     *         ),
     *     ),
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    /**
     * @OA\Get(
     *     path="/api/user",
     *     tags={"User"},
     *     summary="Returns a User whom Authenticated",
     *     description="Authenticated User",
     *     operationId="getAuthenticatedUser",
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *         response="default",
     *         description="successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="number", example="321"),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", format="email", type="string", example="johndoe@mail.com"),
     *                 @OA\Property(property="email_verified_at", type="string"),
     *                 @OA\Property(property="updated_at", type="string"),
     *                 @OA\Property(property="created_at", type="string"),
     *             ),
     *         ),
     *     ),
     * )
     */
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }
}
