<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Enums\eStatus;
use App\Models\User;
use DateTime;

class UserRepository extends RepositoryBase{
    public function __construct(public User $model)
    {}
    public function  currentUser($id){
        return $this->model->where('users.id',$id)->first();
    }
    public function userByStatusForProf($status){
        return $this->model->where('status',$status)->select('id','first_name','last_name','profession','description','url_file')
         ->oldest('first_name','last_name')->get(); 
    }
    public function searchUserText($search,$country,$categorieId,$moduleId,$is_valide,$dateBegin,$dateEnd){
        return $this->model->when($search!='default',function($query)use($search){
                 $query->where(function($q)use($search){
                    $q->where('email','like','%'.$search.'%')
                    ->orWhere('first_name','like','%'.$search.'%')
                    ->orWhere('last_name','like','%'.$search.'%');
                 });
        })->when($country!='default',function($query)use($country){
            $query->where('users.country',$country);
        })->join('module_users','module_users.user_id','=','users.id')
        ->join('modules','module_users.module_id','=','modules.id')
        ->join('categories','categories.id','=','modules.categorie_id')
        ->select('users.*','module_users.id as subscription_id','module_users.title','somme','module_users.type','status_attestation','module_users.is_valide','module_users.url_attestation','module_users.name_attestation','module_users.description as description','module_users.user_id as user_id','module_users.module_id as module_id','module_users.id as module_users_id',
          'module_users.created_at as subscription_created_at','module_users.updated_at as subscription_updated_at','module_users.tel as subscription_tel',
          'modules.id as module_id','modules.title as module_title','categories.name as categorie_name','categories.id as categorie_id')
        ->when($categorieId!='default',function($query)use($categorieId){
            $query->where('categories.id',$categorieId);
        })->when($moduleId!='default',function($query)use($moduleId){
            $query->where('modules.id',$moduleId);
        })->when($is_valide!='default',function($query)use($is_valide){
            $query->where('module_users.is_valide',$is_valide);
        })->when(($dateEnd!='default' || $dateBegin!='default'),function($query)use($dateEnd,$dateBegin){
            $query->whereDate('module_users.created_at','<=',$dateEnd)
            ->whereDate('module_users.created_at','>=',$dateBegin);
        })->where('users.email','!=','akydemy@gmail.com')
        ->whereIn('users.status',[eStatus::ETUDIANT->value,eStatus::AUTRE->value])->latest('module_users.created_at','first_name','last_name')->paginate($this->nbr);
    }

    public function searchAllUser($search,$country,$dateBegin,$dateEnd){
        
        return $this->model->when($search!='default',function($query)use($search){
                 $query->where(function($q)use($search){
                    $q->where('email','like','%'.$search.'%')
                    ->orWhere('first_name','like','%'.$search.'%')
                    ->orWhere('last_name','like','%'.$search.'%');
                 });
        })->when($country!='default',function($query)use($country){
            $query->where('country',$country);
        })->when(($dateEnd!='default' || $dateBegin!='default'),function($query)use($dateEnd,$dateBegin){
            $query->whereDate('created_at','<=',$dateEnd)
            ->whereDate('created_at','>=',$dateBegin);
        })->where('users.email','!=','akydemy@gmail.com')->latest('created_at','fist_name','last_name','updated_at')->paginate($this->nbr); 
    }
    public function currentUserModules(){
        return $this->model->where('id',auth()->user()->id)
         ->with(['subscriptions'=>function($query){
            $query->where('is_valide',1)
            ->with(['modules'=>function($query){
                $query->where('is_active',1)->select('modules.id','title','sub_title','url_file','name_file','created_at','updated_at')
                ->with(['ressourceModdules'=>function($q){
                      return $q->
                       where([
                          ['url_movie','!=',null],
                          ['url_movie','!=',''],
                          ['url_movie','!=','null'],
                       ]);
                }]);
            }]);
         }])?->first();
    }
    
    public  function currentUserEvaluationModule(){
        // RETURN MODULE FOR EVALUATION ACTIVE 
        return $this->model->
         where('users.id',auth()->user()->id)
        ->where('module_users.is_valide',1)
        ->join('module_users','module_users.user_id','=','users.id')
        ->join('modules','modules.id','=','module_users.module_id')
        ->select('modules.id','modules.title','modules.url_file')
        ->paginate($this->nbr);
    }
    public  function currentUserEvaluations($moduleId){
        return $this->model->
         where('users.id',auth()->user()->id)
        ->where('modules.id',$moduleId)
        ->where('module_users.is_valide',1)
        ->where('evaluations.published',1)
        ->join('module_users','module_users.user_id','=','users.id')
        ->join('modules','modules.id','=','module_users.module_id')
        ->join('evaluations','evaluations.module_id','=','modules.id')
        ->leftJoin('note_studiants','note_studiants.evaluation_id','=','evaluations.id')
        ->select('evaluations.*',
        'note_studiants.id as note_studiant_id',
        // 'note','note_teacher',
        'note_studiants.url_file as note_studiant_url_file',
        'note_studiants.name_file as note_studiant_name_file',
        'evaluations.module_id as note_studiant_module_id'
        )
        ->latest('evaluations.created_at')->paginate($this->nbr);
    }

    public  function currentUserNotes(){
        return $this->model->
         where('users.id',auth()->user()->id)
        ->where('module_users.is_valide',1)
        ->where('evaluations.published',1)
        ->where('evaluations.is_closed',1)
        ->join('module_users','module_users.user_id','=','users.id')
        ->join('modules','modules.id','=','module_users.module_id')
        ->join('evaluations','evaluations.module_id','=','modules.id')
        ->join('note_studiants','note_studiants.evaluation_id','=','evaluations.id')
        ->select('evaluations.id as id',
        'evaluations.visibility_date_limit',
        'evaluations.type as type',
        'evaluations.title as title',
        'note_studiants.note_teacher as note_teacher',
      )->paginate($this->nbr);
    }
    
    // public  function currentUserEvaluationModule(){
    //     return $this->model->where('id',auth()->user()->id)->with(['cours'=>function($query){
    //          return $query->select('modules.id','modules.title','url_file')
    //           ->with(['evaluations'=>function($query){
    //              return $query->where('published',true)->with('noteStudiants');
    //           }]);
    //     }])->first();
    // }
    public function noteStudiants($search,$country,$moduleId,$date,$type){
        return $this->model->when($search!='default',function($query)use($search){
                 $query->where(function($q)use($search){
                    $q->where('email','like','%'.$search.'%')
                    ->orWhere('first_name','like','%'.$search.'%')
                    ->orWhere('last_name','like','%'.$search.'%');
                });
        })->when($country!='default',function($query)use($country){
            $query->where('users.country',$country);
        })->when($type!='default',function($query)use($type){
            $query->where('evals.type',$type);
        })->join('note_studiants as noteStds','noteStds.user_id','=','users.id')
        ->join('modules as mod','mod.id','=','noteStds.module_id')
        ->join('evaluations as evals','evals.id','=','noteStds.evaluation_id')
        ->select('users.*','noteStds.note_teacher','mod.title as title_module','noteStds.module_id',
          'evals.id as evaluations_id','evals.title as evaluation_title','evals.is_closed as is_closed','evals.type as type','evals.visibility_date_limit')
       ->when($moduleId!='default',function($query)use($moduleId){
            $query->where('mod.id',$moduleId);
        })->when(($date!='default'),function($query)use($date){
            $query->whereDate('evals.visibility_date_limit','=',$date);
        })->where('users.email','!=','akydemy@gmail.com')
        ->whereIn('users.status',[eStatus::ETUDIANT->value,eStatus::AUTRE->value])
        ->latest('noteStds.created_at','first_name','last_name')->paginate($this->nbr);
    }
}
