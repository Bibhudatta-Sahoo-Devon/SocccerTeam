<?php
/**
 * Created by PhpStorm.
 * User: BSAHOO
 * Date: 29-04-2023
 * Time: 11:14 AM
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"Login"},
     *      summary="To login to soccer",
     *      description="To get access token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login successfully",
     *          @OA\JsonContent(ref="#/components/schemas/LoginResponse")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return new JsonResponse([
                'message' => 'Invalid Credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        if ($user->role == 'A')
            $token = $user->createToken('adminToken', ['admin'])->plainTextToken;
        else
            $token = $user->createToken('userToken', ['user'])->plainTextToken;

        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }


    public function logout(Request $request): JsonResponse
    {
        $userAuthToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($userAuthToken);
        $token->delete();
        return new JsonResponse(['message' => 'Logout Successfully'], Response::HTTP_OK);
    }

}