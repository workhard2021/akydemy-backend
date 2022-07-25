<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\ModuleUser;

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
}
