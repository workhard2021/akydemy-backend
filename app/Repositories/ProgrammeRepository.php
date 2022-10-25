<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Programme;
class ProgrammeRepository extends RepositoryBase{
    public function __construct(public Programme $model)
    {}
    public function allPublic($search){
        return $this->model->where('is_active',true)
        ->when($search!="default",function($query)use($search){
          $query->where([['title','like','%'.$search.'%'],['is_active',true]]
          )->orWhere([['sub_title','like','%'.$search.'%'],['is_active',true]])
          ->orWhere([['title','like','%'.strtoupper($search).'%'],['is_active',true]])
          ->orWhere([['sub_title','like','%'.strtoupper($search).'%'],['is_active',true]]);
        })->paginate($this->nbr);
    }
    public function findProgrammeWithProf($id){
         return $this->model->where('programmes.id',$id)
         ->join('modules','modules.id','=','programmes.module_id')
         ->join('users','users.id','=','modules.owner_id')
         ->select(
                'programmes.id',
                'programmes.title',
                'programmes.description',
                'programmes.url_file_dowload',
                'programmes.module_id as module_id',
                'users.first_name',
                'users.last_name',
                'users.profession',
                'users.description as user_description',
                'users.url_file as user_url_file')->first();
    }
}
