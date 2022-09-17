<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Quiz;

class QuizRepository extends RepositoryBase{
    public function __construct(public Quiz $model)
    {}
    public function quizzesStudiant(){
        return 'a traiter quiz etudiant avec les modules';
    }
    public function quizzesModule($id){
      return $this->findManyByColumn($id,'module_id');
    }
}
