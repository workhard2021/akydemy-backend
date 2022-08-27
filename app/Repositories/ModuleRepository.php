<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Module;

class ModuleRepository extends RepositoryBase{
    public function __construct(public Module $model)
    {}
    public function allPublic(){
       return $this->model->where('is_active',1)->get();
    }
    public function showModuleRessource($id){
        return $this->model->where($this->model->getKeyName(),$id)
         ->with('ressourceModdules')->first();
    }
    public function moduleforExams($search){
       return $this->model->withCount('evaluations as count')
            ->when($search!='default',function($query)use($search){
                 $query->where('title','like','%'.$search.'%'
                 )->orWhere('sub_title','like','%'.$search.'%');
            })->paginate($this->nbr);
    }
}
