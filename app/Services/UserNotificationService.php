<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\UserNotificationRepository;

 class UserNotificationService extends ServiceBase{
     public function __construct(public UserNotificationRepository $repos){}
     public function getModel(){
         return $this->repos->model;
     }
     
    public function update($id,$data,$view_notif=true){
        return $this->repos->model->where($this->repos->model->getKeyName(),$id)
            ->where('user_id',$data['user_id'])
            ->where('event_id',$data['event_id'])
            ->update(['view_notif'=>$view_notif]);
    }
 }