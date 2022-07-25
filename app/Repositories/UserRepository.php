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
    public function  userByStatus($status){
        return $this->findManyByColumn($status,'status'); 
    }
    public function searchUserText($search,$countryId,$categorieId,$dateBegin,$dateEnd){
        //  $dateNew = DateTime::createFromFormat('m-d-Y', '2022-07-29')->format('Y/m/d');
        //  return $dateNew
        $data=$this->model->when($search!='default',function($query)use($search){
                 $query->where(function($q)use($search){
                    $q->where('email','like','%'.$search.'%')
                    ->orWhere('first_name','like','%'.$search.'%')
                    ->orWhere('last_name','like','%'.$search.'%');
                 });
        })->join('module_users','module_users.user_id','=','users.id')
        ->join('modules','module_users.module_id','=','modules.id')
        ->join('categories','categories.id','=','modules.categorie_id')
        ->select('users.*','module_users.id as subscription_id','module_users.title','somme','type','status_attestation','is_valide','file_attestaion_url','user_id',
          'module_users.created_at as subscription_created_at','module_users.updated_at as subscription_updated_at',
          'modules.id as module_id','categories.name as categorie_name','categories.id as categorie_id')
        ->when($countryId!='default',function($query)use($countryId){
            $query->where('users.country_id',$countryId);
        })
        ->when($categorieId!='default',function($query)use($categorieId){
            $query->where('categories.id',$categorieId);
        })->when(($dateEnd!='default' || $dateBegin!='default'),function($query)use($dateEnd,$dateBegin){
            $query->whereDate('module_users.created_at','<=',$dateEnd)
            ->whereDate('module_users.created_at','>=',$dateBegin);
        })->paginate($this->nbr);
        return $data;
    }

}
