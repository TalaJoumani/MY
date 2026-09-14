<?php

namespace App\Http\Controllers;
use App\Services\AdminServices;
use Illuminate\Http\Request;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;


class AdminController extends Controller
{
      protected AdminServices $adminService;
    public function __construct(AdminServices $adminService)
    {
        $this->adminService = $adminService;
    }

    public function addUser(AddUserRequest $adduserrequest)
    {
        $data = $adduserrequest->validated();
        $user = $this->adminService->addUser($data);

        return response()->json([
            'message' => 'User added successfully',
             'user' => $user
             ],201);
    }

      public function deleteUser(Request $request){
        if(auth('sanctum')->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }
        $deleted = $this->adminService->deleteUser($request->id);
        if (!$deleted) {
            return response()->json([
                'message' => 'cannot delete user',
            ],404);
        }
        return response()->json([
            'message' => 'User deleted successfully',
        ],200);
    }


    // Get all admins
    public function getAllUsers(){
        if(auth('sanctum')->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }
        $users=$this->adminService->getAllUsers();
        return response()->json([
            'message' => 'Users retrieved successfully',
            'users' => $users
        ],200);
    }

    public function updateUser(UpdateUserRequest $request){
        if(auth('sanctum')->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }
      $user=$this->adminService->updateUser($request->id, $request->validated());
      
        if(!$user){
            return response()->json([
                'message' => 'User not found',
            ],404);
        }
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ],200);

    }

    
}
