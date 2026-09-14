<?php
namespace App\Services;

use App\Models\code;
use App\Models\User;

class UserServices{
   public function generateUserCode(int $userId,float $discount){
    $user=User::find($userId);
    if(!$user){
        return response()->json([
            'message' => 'User not found',
        ],404);
    }

    if($discount> $user->max_discount){
        return response()->json([
            'message' => 'Discount exceeds users maximum discount',
        ],400);
    }
    $firstName=$user-> first_name;
    $lastName=$user-> last_name;
    $discountInt=intval($discount);
    $codeString=$firstName.$discountInt;
    $counter=1;
    while(code::where('code',$codeString)->exists()){
        $lastNamePart=substr($lastName,0,$counter);
        $codeString=$firstName.$lastNamePart.$discountInt;
        $counter++;
        if($counter>strlen($lastName)){
           $codeString=$firstName.$lastName.$discountInt.rand(100,999);
           break;
        }
    }
    $code=Code::create([
        'user_id'=>$userId,
         'code'=>$codeString,
        'discount'=>$discount,
        'is_active'=>true,
    ]);

    return response()->json([
        'message' => 'Code generated successfully',
        'code' => $code,
    ],201);
   }
}