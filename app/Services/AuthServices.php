<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthServices
{
     public function login(array $credentials)
   {
       $user=User::where('email',$credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
                ], 401);
        }

        if(isset($credentials['fcm_token']) && $credentials['fcm_token']!=null){
            $user->update([
                'fcm_token' => $credentials['fcm_token']
            ]);
        }
        
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 200);
   }

    public function logout(Request $request)
    {
        if($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logout successful',
            ], 200);
        }
    }
  
}