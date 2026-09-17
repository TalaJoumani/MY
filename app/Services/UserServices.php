<?php
namespace App\Services;

use App\Models\code;
use App\Models\transaction;
use App\Models\User;

class UserServices{
   public function generateUserCode(int $userId,float $discount){
    $user=User::find($userId);
    if(!$user){
        return response()->json([
            'message' => 'User not found',
        ],404);
    }

    $existCode=Code::where('user_id',$user->id)->where('discount',$discount)->first();
    if($existCode){
        return response()->json([
            'message'=>'you already have a code for this discount',
            'code'=>$existCode,
        ]);
    }
    if($discount> $user->max_discount){
        return response()->json([
            'message' => 'Discount exceeds users maximum discount',
        ],400);
    }

    $firstName=$user-> first_name;
    $lastName=$user-> last_name;
    $discountInt=intval($discount);
    $codeString=$firstName.'_'.$discountInt;
    $counter=1;
    while(code::where('code',$codeString)->exists()){
        $lastNamePart=substr($lastName,0,$counter);
        $codeString=$firstName.'_'.$lastNamePart.'_'.$discountInt;
        $counter++;
        if($counter>strlen($lastName)){
           $codeString=$firstName.'_'.$lastName.'_'.$discountInt.'_'.rand(100,999);
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

   public function updateCodeStatus(int $userId, int $codeId){
    $code=Code::where('id',$codeId)->where('user_id',$userId)->first();
    if(!$code){
        return response()->json([
            'message' => 'Code not found or authorized',
        ],404);
    }
    $code->is_active=!$code->is_active;
    $code->save();
    $statustext=$code->is_active?'active':'inactive';
    return response()->json([
        'message' => 'Code status updated successfully',
        'code' => $code,
        'status'=>$statustext,
    ],200);
   }

   public function getUserEarnings(int $userId){
    $userCodeIds=Code::where('user_id',$userId)->pluck('id');
    if($userCodeIds->isEmpty()){
        return response()->json([
            'message' => 'No codes found for this user',
            'earnings'=>0,
            'transactions'=>[],
        ],200);
    }
    $transactions=transaction::whereIn('code_id',$userCodeIds)->get();
    $reportingData=[];
    $totalEarnings=0;
    $patientCount=1;
    foreach($transactions as $transaction){
       $reportingData[]=[
        'patient_label'=>"#patient".$patientCount,
        'total_amount'=>$transaction->total_amount,
        'discount'=>$transaction->code->discount,
        'final_amount'=>$transaction->final_amount,
        'user_commission'=>$transaction->user_commission,
        'date'=>$transaction->created_at->format('Y-m-d H:i:s'),
       ];
    
    $totalEarnings+=(float)$transaction->user_commission;
    $patientCount++;
    }
    return response()->json([
        'message' => 'Earnings retrieved successfully',
        'earnings'=>$totalEarnings,
        'transactions'=>$reportingData,
        'transaction_count'=>count($reportingData),
    ],200);
   }
}