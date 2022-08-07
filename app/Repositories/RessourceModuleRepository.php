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
     
     public function searchResourceModuleStudiant($search='',$userID){
        return $this->model
        ->when($search,function($query)use($search){
            return $query->where('modules.title','like','%'.$search.'%')
             ->orWhere('ressources_modules.title','like','%'.$search.'%');
        })->where(function($query){
             $query->where('ressources_modules.is_public',0)
             ->orWhere('ressources_modules.is_public',1); 
        })->where('modules.is_active',1)
        ->where('users.id',$userID)
        ->join('modules','modules.id','=','ressources_modules.module_id')
         ->join('module_users','module_users.module_id','=','modules.id')
        ->join('users','users.id','=','module_users.user_id')
        ->select('ressources_modules.*','modules.title as module_title','users.id as user_id')->paginate($this->nbr);
     }
}
