<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\AskEvaluation;
class AskEvaluationRepository extends RepositoryBase{
    public function __construct(public AskEvaluation $model)
    {}
    public function search($text,$moduleId,$dateBegin,$dateEnd){
        return $this->model
          ->join('users','users.id','=','ask_evaluations.user_id')
          ->join('modules','modules.id','=','ask_evaluations.module_id')
          ->when(($text!="default"),function($query)use($text){
            $query->where('users.email','like','%'.strtoupper($text).'%'
            )->orWhere('users.first_name','like','%'.strtoupper($text).'%')
            ->orWhere('users.last_name','like','%'.strtoupper($text).'%');
          })->when(($moduleId!="default"),function($query)use($moduleId){
            $query->where('modules.id',$moduleId);
          })->when(($dateEnd!='default' || $dateBegin!='default'),function($query)use($dateEnd,$dateBegin){
            $query->whereDate('ask_evaluations.created_at','<=',$dateEnd)
            ->whereDate('ask_evaluations.created_at','>=',$dateBegin);
          })->where('modules.is_active',true)
          ->where('ask_evaluations.ask',true)
          ->select('ask_evaluations.id as ask_evaluations_id','ask_evaluations.accepted','ask_evaluations.created_at','users.id as user_id','users.first_name','users.last_name','users.email','users.url_file','modules.title')->paginate($this->nbr);
    }
}
