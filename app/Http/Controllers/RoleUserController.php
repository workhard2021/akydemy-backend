<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\RoleUserService;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function __construct(private RoleUserService $service)
    {}
    public function store(Request $request){
          $data = $request->validate([
            'role_ids' =>'nullable',
            'user_id' => 'required|numeric',
          ]);
          return response($this->service->updateRoleUser($data));
    }
}
