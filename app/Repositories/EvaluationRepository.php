<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Evaluation;

class EvaluationRepository extends RepositoryBase{
    public function __construct(public Evaluation $model,private ModuleRepository $moduleRepos)
    {}
    
    public function findModule($moduleId)
    {
       return $this->moduleRepos->find($moduleId);
    }
    public function existModule($moduleId,$type,$dateLimit)
    {
        return $this->model->where([
           ['module_id',$moduleId],
           ['type',$type],
           ['visibility_date_limit',$dateLimit]
        ])->exists();
    }
}
