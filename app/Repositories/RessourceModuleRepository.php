<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\RessourcesModule;

class RessourceModuleRepository extends RepositoryBase{
    public function __construct(public RessourcesModule $model)
    {}
    public function ressourceFormModule($id){
        return $this->model->where('module_id',$id)->paginate($this->nbr);
     }
     public function searchResourceModule($modulId,$search='',$published=null){
        return $this->model->search($search)
        ->when($modulId,function($query)use($modulId){
            return $query->where('module_id', $modulId);
        })->when($published==1,function($query)use($published){
            return $query->where('is_public', $published);
        })->paginate($this->nbr);
     }
}
