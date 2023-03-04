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
    
        $this->repos->model->where($this->repos->model->getKeyName(),$id)
        ->where('is_teacher',false)->where('user_id',auth()->user()->id)
            ->where('event_id',$data['event_id'])
            ->update(['view_notif'=>$view_notif]);

        $this->repos->model->where($this->repos->model->getKeyName(),$id)
            ->where('is_teacher',true)
            ->where('teacher_id',auth()->user()->id)
            ->where('event_id',$data['event_id'])
            ->update(['view_notif'=>$view_notif]);
    }
 }