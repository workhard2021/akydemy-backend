<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\UserNotification;

class UserNotificationRepository extends RepositoryBase{
    public function __construct(public UserNotification $model)
    {}
    public function currentUserNotif(){
        return $this->model->where([
             ['user_id',auth()->user()->id],
             ['is_teacher',false],
          ])->latest('created_at')->paginate(12);
    }
    public function currentTecherNotif(){
        
        return $this->model->where([
             ['teacher_id',auth()->user()->id],
             ['is_teacher',1],
          ])->latest('created_at')->paginate(12);
    }
    public function currentUserNoteNotRead($view_notif=false){
        $userId=auth()->user()->id;
        return $this->model->where([ 
                ['user_id',$userId],
                ['view_notif',$view_notif],
                ['is_teacher',false]
             ])->orWhere([
                ['teacher_id',$userId],
                ['view_notif',$view_notif]
             ])->count();
     }  
}
