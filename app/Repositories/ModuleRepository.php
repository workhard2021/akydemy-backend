<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Module;

class ModuleRepository extends RepositoryBase{
    public function __construct(public Module $model)
    {}
    public function allPublic($search){
      
      return $this->model->where('is_active',true)
         ->when($search!='default',function($query)use($search){
          $query->where([['title','like','%'.$search.'%'],['is_active',true]]
         )->orWhere([['sub_title','like','%'.$search.'%'],['is_active',true]])
          ->orWhere([['title','like','%'.strtoupper($search).'%'],['is_active',true]])
          ->orWhere([['sub_title','like','%'.strtoupper($search).'%'],['is_active',true]]);
      })->join('users as teacher','teacher.id','=','modules.owner_id')
      ->orderBy('modules.updated_at','desc')->select('modules.id','modules.title','modules.sub_title','modules.url_file as image_module','teacher.first_name','teacher.last_name','teacher.url_file as image_teacher')
      ->paginate($this->nbr);
    }

    public function showModuleRessource($id){
        return $this->model->where($this->model->getKeyName(),$id)
         ->with(['ressourceModdules'=>function($q){
           return $q->where('url_pdf',null)
            ->orWhere('url_pdf','null')
            ->orWhere('url_pdf','')->oldest('created_at');
         }])->first();
    }
    public function moduleforExams($search){
       return $this->model->withCount('evaluations as count')
            ->when($search!='default',function($query)use($search){
                 $query->where('title','like','%'.$search.'%'
                 )->orWhere('sub_title','like','%'.$search.'%')
                 ->orWhere('title','like','%'.strtoupper($search).'%')
                 ->orWhere('sub_title','like','%'.strtoupper($search).'%');
            })->paginate($this->nbr);
    }

    public function listNotPaginate(){
      return $this->model->select('id','title')->orderBy('created_at','asc')->get();
    }

    public function listNotPaginatePublic(){
      return $this->model->select('id','title')->where('is_active',true)->orderBy('created_at','asc')->get();
    }  
    public function adminModulesVideo(){
        return $this->model->where('is_active',1)
            ->leftJoin('ask_evaluations','ask_evaluations.module_id','=','modules.id')
            ->select('modules.id','title','sub_title','url_file','name_file','ask_evaluations.ask','ask_evaluations.accepted','modules.created_at','modules.updated_at')
            ->with(['ressourceModdules'=>function($q){
              return $q->where([
                 ['url_movie','!=',null],
                 ['url_movie','!=',''],
                 ['url_movie','!=','null'],
              ]);
        }])->get();
    }    
}
