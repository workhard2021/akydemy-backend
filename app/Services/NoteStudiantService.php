<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\NoteStudiantRepository;

class NoteStudiantService extends ServiceBase{
     public function __construct(public NoteStudiantRepository $repos){}
     public function updateNote($id,$data){
          $item=$this->repos->model
          ->where('evaluation_id',$data['evaluation_id'])
          ->where('user_id',$data['user_id'])
          ->where('id',$id)->first();
          $item?->update($data);
          return $item;
     }
     public function updateNoteWithEvaluation($evaluation_id,$data){
          $item=$this->repos->model
          ->where('evaluation_id',$evaluation_id);
          $item?->update($data);
          return $item;
     }
}