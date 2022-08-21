<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
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
        ->select('users.*','module_users.id as subscription_id','module_users.title','somme','type','status_attestation','is_valide','module_users.url_attestation','module_users.name_attestation','module_users.description as description','user_id','module_id','module_users.id as module_users_id',
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
        })->latest('module_users.created_at','first_name','last_name')->paginate($this->nbr);
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
        }) ->latest('created_at','fist_name','last_name','updated_at')->paginate($this->nbr); 
    }
    public function currentUserModules(){
        return $this->model->where('id',auth()->user()->id)
         ->with(['subscriptions'=>function($query){
            $query->where('is_valide',1)
            ->with(['modules'=>function($query){
                $query->where('is_active',1)->select('modules.id','title','sub_title','url_file','name_file','created_at','updated_at')
                ->with(['ressourceModdules']);
            }]);
         }])?->first();
    }
    public  function currentUserEvaluationModule(){
        return $this->model->where('id',auth()->user()->id)->with(['cours'=>function($query){
             return $query->select('modules.id','modules.title','url_file')
              ->with(['evaluations'=>function($query){
                 return $query->where('published',true)->with('noteStudiants');
              }]);
        }])->first();
    }
}
