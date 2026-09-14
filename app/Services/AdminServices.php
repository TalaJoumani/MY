<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomMail;

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
    

    
}