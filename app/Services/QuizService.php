<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\QuizRepository;

 class QuizService extends ServiceBase{
     public function __construct(public QuizRepository $repos){}
     public function deleteQuizModule($id){
        return $this->repos->model->where('module_id',$id)->delete();
     }
     public function updateQuizModule($id,$data){
        $item=$this->repos->model->firstWhere('module_id',$id);
        $item->update($data);
        return $item;
     }
 }