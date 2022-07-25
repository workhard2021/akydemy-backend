<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Programme;
class ProgrammeRepository extends RepositoryBase{
    public function __construct(public Programme $model)
    {}
    public function allPublic(){
        return $this->model->all();
    }
}
