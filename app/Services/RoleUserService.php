<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Models\User;
use App\Repositories\RoleUserRepository;
class RoleUserService extends ServiceBase{
    public function __construct(public RoleUserRepository $repos,public User $user){}
    public function updateRoleUser($data){
        $user=$this->user->find($data['user_id']);
        if(!$user){
            return false;
        }
        return $user->roles()->sync($data['role_ids']);
    }
}