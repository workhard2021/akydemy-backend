<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Categories;

class CategorieRepository extends RepositoryBase{
    public function __construct(public Categories $model)
    {}
    public function searchText($search){
        if($search){
           return $this->model->search($search)->paginate($this->nbr);
        }else{
           return $this->model->latest('updated_at','created_at')->paginate($this->nbr);
        }
    }
    public function listNotPaginate(){
        return $this->model->select('id','name')->orderBy('updated_at','asc')->get();
    }
    public function listNotPaginatePublic(){
        return $this->model->whereHas("modules")->orderBy('name','asc')->get(['id','name','title','url_file','name_file']);
    }
    
    public function find($id){
        return $this->model->getModel()->withCount('modules as count')->find($id);
    }
}
