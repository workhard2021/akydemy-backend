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

    public function searchResourceModuleAdmin($search){
        return $this->model
        ->when($search,function($query)use($search){
            return $query->where([
               ['modules.title','like','%'.$search.'%'],
               ['modules.is_active','=',true],
               ['ressources_modules.name_movie','=',null],
               ['ressources_modules.name_pdf','!=',null],
            ])->orWhere([
               ['ressources_modules.title','like','%'.$search.'%'],
               ['modules.is_active','=',true],
               ['ressources_modules.name_movie','=',null],
               ['ressources_modules.name_pdf','!=',null],
            ]);
        })->when(!$search,function($query){
            $query->where([
                ['ressources_modules.name_movie','=',null],
                ['modules.is_active','=',true],
                ['ressources_modules.name_pdf','!=',null],
            ]);
        })
        ->join('modules','modules.id','=','ressources_modules.module_id')
        ->select('ressources_modules.id as id','ressources_modules.title',
           'ressources_modules.url_pdf','ressources_modules.name_pdf',
           'ressources_modules.is_public','ressources_modules.is_default',
           'ressources_modules.module_id','modules.title as module_title',
           'ressources_modules.created_at','ressources_modules.updated_at')
        ->oldest('ressources_modules.updated_at','ressources_modules.created_at')->paginate($this->nbr);
    }

    public function searchResourceModuleStudiant($search){
        return $this->model
        ->when($search,function($query)use($search){
            return $query->where([
               ['modules.title','like','%'.$search.'%'],
               ['modules.is_active','=',true],
               ['ressources_modules.name_movie','=',null],
               ['module_users.user_id','=',auth()->user()->id],
               ['ressources_modules.name_pdf','!=',null],
            ])
            ->orWhere([
               ['ressources_modules.title','like','%'.$search.'%'],
               ['modules.is_active','=',true],
               ['ressources_modules.name_movie','=',null],
               ['module_users.is_valide','=',true],
               ['module_users.user_id','=',auth()->user()->id],
               ['ressources_modules.name_pdf','!=',null],
            ]);
        })->when(!$search,function($query){
            $query->where([
                ['ressources_modules.name_movie','=',null],
                ['modules.is_active','=',true],
                ['module_users.is_valide','=',true],
                ['module_users.user_id','=',auth()->user()->id],
                ['ressources_modules.name_pdf','!=',null],
            ]);
        })
        ->join('modules','modules.id','=','ressources_modules.module_id')
        ->join('module_users','module_users.module_id','=','modules.id')
        ->join('users','users.id','=','module_users.user_id')
        ->select('ressources_modules.*','modules.title as module_title','users.id as user_id')
        ->latest('ressources_modules.updated_at','ressources_modules.created_at')->paginate($this->nbr);
     }
}
