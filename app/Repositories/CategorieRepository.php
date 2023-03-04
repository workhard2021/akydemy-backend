<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Categories;

class CategorieRepository extends RepositoryBase{
    public function __construct(public Categories $model)
    {}
    public function listNotPaginate(){
        return $this->model->select('id','name')->orderBy('updated_at','asc')->get();
    }
    public function listNotPaginatePublic(){
        return $this->model->whereHas("modules")->orderBy('name','asc')->get(['id','name','title','url_file','name_file']);
    }
}
