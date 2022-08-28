<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Enums\eStatus;
use App\Models\NoteStudiant;

class NoteStudiantRepository extends RepositoryBase{
    public function __construct(public NoteStudiant $model,private ModuleRepository $reposModule,private EvaluationRepository $reposEvaluation)
    {}

    public function exist($evaluationId,$userId){
       return $this->model->where('evaluation_id',$evaluationId)
         ->where('user_id',$userId)->first();
    }

    public function findEvaluation($evaluationId){
       return $this->reposEvaluation->findOneByColumn('id',$evaluationId);
    }
    public function noteStudiantWithInfo($search='',$dateBegin,$dateEnd){
        $user=auth()->user();
        return $this->model->
              join('users','users.id','=','note_studiants.user_id')
              ->join('users as teacher','teacher.id','=','note_studiants.teacher_id')
              ->join('evaluations','evaluations.id','=','note_studiants.evaluation_id')
              ->when($search!='default',function($query)use($search){
                $query->where(function($query)use($search){
                  return $query->where('users.email','like','%'.$search.'%')
                  ->orWhere('users.first_name','like','%'.$search.'%')
                  ->orWhere('users.last_name','like','%'.$search.'%')
                  ->orWhere('teacher.email','like','%'.$search.'%')
                  ->orWhere('note_studiants.title','like','%'.$search.'%')
                  ->orWhere('evaluations.title','like','%'.$search.'%')
                  ->orWhere('evaluations.type','like','%'.$search.'%');
                });
              })->where(function($query)use($dateEnd,$dateBegin){
                $query->when(($dateEnd!='default' || $dateBegin!='default'),function($query)use($dateEnd,$dateBegin){
                  $query->whereDate('evaluations.created_at','<=',$dateEnd)
                   ->whereDate('evaluations.created_at','>=',$dateBegin);
                });
              })->when($user->status==eStatus::PROFESSEUR->value,function($query)use($user){
                 $query->where('note_studiants.teacher_id','=',$user->id);
              })->select('note_studiants.*','users.id as user_id','users.first_name','users.last_name',
              'users.url_file as user_url_file','users.tel','users.email','teacher.id as teacher_id',
              'teacher.first_name as teacher_first_name','teacher.last_name as teacher_last_name',
              'teacher.email as teacher_email','evaluations.title as session_title',
              'evaluations.visibility_date_limit','evaluations.type')
              ->latest('evaluations.created_at','users.first_name','users.last_name')
              ->paginate($this->nbr);
    } 

    public function currentUserNote($moduleId){
       $userId=auth()->user()->id;
       return $this->model->where([ 
               ['user_id',$userId],
               ['module_id',$moduleId],
            ])->get();
    }  
}
