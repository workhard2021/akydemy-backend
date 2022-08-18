<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Module;
use App\Models\ModuleUser;
use App\Services\UserNotificationService;

class ModuleUserRepository extends RepositoryBase{
    public function __construct(public ModuleUser $model)
    {}
    public function searchText($search = '')
    {
        return $this->model->all();
    }
    public function moduelExistForUser($userId,$moduleId){
        return $this->model->where([
               ['user_id','=',$userId],
               ['module_id','=',$moduleId]
        ])->exists();
    }
    public function findModule($moduleId){
        return Module::find($moduleId,['id','title','price','promo_price','owner_id']);
    }
    public function attestationUser($userId){
        return $this->model->where([ ['user_id',$userId],['is_valide',1]])->select('id','title','is_valide','type','name_attestation','url_attestation','updated_at','description')
        ->get();
    }
}
