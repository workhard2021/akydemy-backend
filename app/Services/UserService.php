<?php

namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService extends ServiceBase
{   
    public $tokenName='token';
    public function __construct(public UserRepository $repos,){}
    public function createOrUpdateProvider($data){
        $user=$this->repos->model->where('email',$data['email'])->first();
        $input=[
            'email'=>$data['email'],
            'first_name' => $data['first_name'],
            'last_name' => '',
            'image' => '',
            'password' => Hash::make('password')
        ];
        if(!$user){
           $user= parent::create($input);
        }
        return $user->createToken($this->tokenName)?->plainTextToken;
    }
    public function create($data)
    {
        return parent::create($data)->createToken($this->tokenName)?->plainTextToken;
    }
    
}