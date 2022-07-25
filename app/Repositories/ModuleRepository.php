<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Module;

class ModuleRepository extends RepositoryBase{
    public function __construct(public Module $model)
    {}
    public function allPublic(){
         return $this->model->all();
    }
}
