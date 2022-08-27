<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Enums\eStatusAttestation;
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
               ['module_id','=',$moduleId],
               ['is_valide','=',true],
        ])->exists();
    }
    public function moduelAndUserExist($userId,$moduleId){
        return $this->model->where([
               ['user_id','=',$userId],
               ['module_id','=',$moduleId],
        ])->exists();
    }
    public function moduelExistForUserCancel($userId,$moduleId){
        return $this->model->where([
               ['user_id','=',$userId],
               ['module_id','=',$moduleId],
               ['is_valide','=',false]
        ])->exists();
    }
    public function subscriber($userId,$moduleId){
        return $this->model->where([
               ['user_id','=',$userId],
               ['module_id','=',$moduleId],
               ['is_valide','=',null]
        ])->exists();
    }
    public function findModule($moduleId){
        return Module::find($moduleId,['id','title','price','promo_price','owner_id']);
    }
    public function attestationUser($userId){
        return $this->model->where([ ['user_id',$userId],['is_valide',1]])
        ->where('status_attestation','!=',eStatusAttestation::AUCUNE->value)
        ->select('id','title','is_valide','type','status_attestation','name_attestation','url_attestation','updated_at','description')
        ->latest('created_at')->get();
    }
}
