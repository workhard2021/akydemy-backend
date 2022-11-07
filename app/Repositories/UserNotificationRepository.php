<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Enums\eStatus;
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
        $user=auth()->user();
        if( $user && $user->status==eStatus::PROFESSEUR->value){
            return $this->model->where([ 
            ['teacher_id',$user->id],
            ['view_notif',$view_notif],
            ['is_teacher',true]
            ])->count();
        }
        return $this->model->where([
             ['is_teacher',false],
             ['user_id',$user->id],
             ['view_notif',$view_notif]
         ])->count();      
     }  
}
