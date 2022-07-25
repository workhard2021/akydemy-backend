<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\NoteStudiant;

class NoteStudiantRepository extends RepositoryBase{
    public function __construct(public NoteStudiant $model)
    {}
    public function getEvaluation($moduleId,$userId){
       return $this->model->where('module_id',$moduleId)->where('user_id',$userId)
         ->count()>0?true:false;
    }
}
