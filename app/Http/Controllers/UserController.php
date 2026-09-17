<?php

namespace App\Http\Controllers;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
      protected UserServices $userService;
    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function generateUserCode(Request $request){
        $userId=auth('sanctum')->user()->id;
        if(auth('sanctum')->user()->role == 'admin'){
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }
        $request->validate([
            'discount' => 'required|numeric',
        ]);
          
        $result=$this->userService->generateUserCode($userId,$request->discount);
        return $result;
    }

    public function updateCodeStatus(Request $request){
        $userId=auth('sanctum')->user()->id;
        if(auth('sanctum')->user()->role == 'admin'){
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }
        $request->validate([
            'code_id' => 'required|integer|exists:codes,id',
        ]);
          
        $result=$this->userService->updateCodeStatus($userId,$request->code_id);
        return $result;
    }

    public function getUserEarnings(){
        $userId=auth('sanctum')->user()->id;
        if(auth('sanctum')->user()->role == 'admin'){
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }
        $result=$this->userService->getUserEarnings($userId);
        return $result;
    }
}
