<?php

namespace App\Services;

use App\Contracts\ServiceBase;
use App\Enums\eRole;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService extends ServiceBase
{   
    public $tokenName='token';
    public function __construct(public UserRepository $repos){}
    public function createOrUpdateProvider($data){
        $user=$this->repos->model->where('email',$data['email'])->first();
        $input=[
            'email'=>$data['email'],
            'first_name' => $data['first_name'],
            'last_name' => '',
            'image' => $data['image']?$data['image']:'',
            'password' => Hash::make('password')
        ];
        if(!$user){
           $user= parent::create($input);
        }
        return ["token"=>$user->createToken($this->tokenName)?->plainTextToken,"user"=>$user];
    }
    public function create($data)
    {
        $user=parent::create($data);
        return ["token"=>$user->createToken($this->tokenName)?->plainTextToken,"user"=>$user];
    }

    public function updateUserRole($id){
        $user=$this->repos->find($id);
        $roleStudiant=Role::where("name",eRole::ETUDIANT->value)->first();
        if($user){
           $roles=$user->roles()->get()->pluck("id")->toArray();
           if(!in_array($roleStudiant->id,$roles)){
             return $user->roles()->attach($roleStudiant->id);  
           }
        }
        return true;
    }
}