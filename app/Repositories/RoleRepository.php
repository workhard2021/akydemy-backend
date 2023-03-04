<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Enums\eStatus;
use App\Models\Role;

class RoleRepository extends RepositoryBase{
    public function __construct(public Role $model)
    {}
    public function getUserRoleProf(){
        $data= $this->model->where('name',eStatus::PROFESSEUR->value)->select('id','name')->with('users:id,first_name,last_name,profession,description,url_file')->first(); 
        return $data?$data->users:[];
    }
}
