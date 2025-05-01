<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * User login
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse{
        $credentials = $request->only('name', 'password');
        $hasConnected = Auth::attempt($credentials);
        if ($hasConnected) {
            $user = Auth::user();
            $token = $user->createToken('user-token')->plainTextToken;
            if(($user->access == 'allowed') && ($user->state == 'allowed')){
                return response()->json([
                    "status"=>"success",
                    'user' => $user,
                    'token' => $token,
                ]);
            }
            else{
                return response()->json(['errors' => 'utilisateur désactivé!']);
            }
        }
        return response()->json(['errors' => 'Identifiant incorrect']);
    }


    /**
     * check user access
     * @param int userId
     * @return JsonResponse
    */
    public function checkUserAccess($userId):JsonResponse
    {
        $user = User::findOrFail($userId);

        if($user->access == 'allowed'){
            return response()->json([
                "status"=>"success"
            ]);
        }
        else{
            return response()->json([
                "errors"=>"denied"
            ]);
        }
    }

}
