<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Enums\eStatus;
use App\Repositories\UserNotificationRepository;

 class UserNotificationService extends ServiceBase{
     public function __construct(public UserNotificationRepository $repos){}
     public function getModel(){
         return $this->repos->model;
     }
     
    public function update($id,$data,$view_notif=true){
        
        if(auth()->user()->status==eStatus::ETUDIANT->value){
            return $this->repos->model->where($this->repos->model->getKeyName(),$id)
            ->when()->where('user_id',auth()->user()->id)
            ->where('event_id',$data['event_id'])
            ->update(['view_notif'=>$view_notif]);
        }
        return $this->repos->model->where($this->repos->model->getKeyName(),$id)
            ->when()->where('teacher_id',auth()->user()->id)
            ->where('event_id',$data['event_id'])
            ->update(['view_notif'=>$view_notif]);
    }
 }