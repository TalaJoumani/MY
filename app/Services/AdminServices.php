<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomMail;
use App\Models\code;
use App\Models\transaction;

class AdminServices{
     public function addUser(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'user',
            'max_discount'=>$data['max_discount'],
        ]);
            Mail::to($user->email)->send(new WelcomMail($user, $data['password']));
        return $user;

    }

    
    public function deleteUser(int $id)
    {
        $user = User::where('id', $id)->where('role', 'user')->first();
        if(!$user) {
            return false;
        }
        return $user->delete();
    }


    public function getAllUsers()
    {
        return User::where('role', 'user')->get();
    }

    public function updateUser(int $id, array $data){
        $user=User::where('id',$id)->where('role','user')->first();
        if(!$user){
            return false;
        }   
        $updateDate=[];
        if(isset($data['first_name'])){
            $updateDate['first_name']=$data['first_name'];
        }
        if(isset($data['last_name'])){
            $updateDate['last_name']=$data['last_name'];
        }
        if(isset($data['email'])){
            $updateDate['email']=$data['email'];
        }
        if(isset($data['max_discount'])){
            $updateDate['max_discount']=$data['max_discount'];
        }
        if(isset($data['password'])){
            $updateDate['password']=Hash::make($data['password']);
        }
        $user->update($updateDate);
        return $user;
    }
    

    public function verifyAndCalculateCode(array $data){
        $code=Code::with('user')->where('code',$data['code'])->first();
        if(!$code){
            return response()->json([
                'message' => 'Code not found',
            ],404);
        }

        if(!$code->is_active){
            return response()->json([
                'message' => 'Code is inactive',
            ],400);
        }

        $totalAmount=$data['total_amount'];
        $user=$code->user;
        $codeDiscount=$code->discount;
        $maxDiscount=$user->max_discount;
        $discountValue=($totalAmount*$codeDiscount)/100;
        $finalAmount=$totalAmount-$discountValue;
        $commissionPercentage=max(0, $maxDiscount-$codeDiscount);
        $userCommission=($totalAmount*$commissionPercentage)/100;
        $transaction=transaction::create([
            'user_id'=>$user->id,
            'code_id'=>$code->id,
            'total_amount'=>$totalAmount,
            'discount'=>$discountValue,
            'final_amount'=>$finalAmount,
            'user_commission'=>$userCommission,
        ]);
        return response()->json([
            'data'=>[
                'code'=>$code->code,
                'user_name'=>$user->first_name.' '.$user->last_name,
                'total_amount'=>$totalAmount,
                'discount_percentage'=>$codeDiscount,
                'discount_value'=>$discountValue,
                'final_amount'=>$finalAmount,
                'user_commission_percentage'=>$commissionPercentage,
                'user_commission'=>$userCommission,
                'transaction_id'=>$transaction->id,
            ]
        ]);
    }

    public function getMonthlyEarningsReport(){
        $transactions=transaction::with('code.user')->get();
        $report=[];
        foreach($transactions as $transaction){
            if(!$transaction->code || !$transaction->code->user){
                continue;
            }
            $userId=$transaction->code->user->id;
            $userName=$transaction->code->user->first_name.' '.$transaction->code->user->last_name;
            $monthKey=$transaction->created_at->format('Y-m');
            if(!isset($report[$monthKey][$userId])){
                $report[$monthKey][$userId]=[
                    'user_id'=>$userId,
                    'user_name'=>$userName,
                    'total_commission'=>0,
                    'transaction_count'=>0,
                ];
            }
            $report[$monthKey][$userId]['total_commission']+=(float)$transaction->user_commission;
            $report[$monthKey][$userId]['transaction_count']+=1;
        }

        $formattedReport=[];
        foreach($report as $month=>$users){
            $formattedReport[]=[
                'month'=>$month,
                'users'=>array_values($users)
            ];
        }

        return response()->json([
            'message' => 'Monthly earnings report retrieved successfully',
            'report'=>$formattedReport,
        ],200);
    }
    
}