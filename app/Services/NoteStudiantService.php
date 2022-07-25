<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\NoteStudiantRepository;

class NoteStudiantService extends ServiceBase{
     public function __construct(public NoteStudiantRepository $repos){}
     public function updateNote($id,$data){
          $item=$this->repos->model->where('module_id',$data['module_id'])
          ->where('user_id',$data['user_id'])
          ->where('id',$id)->first();
          $item?->update($data);
          return $item;
     }
}