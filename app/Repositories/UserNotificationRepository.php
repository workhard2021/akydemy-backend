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
          ])->latest('created_at')->paginate(12);
    }
    public function currentUserNoteNotRead($view_notif=false){
        $user=auth()->user();
        return $this->model->where([
             ['user_id',$user->id],
             ['view_notif',$view_notif]
         ])->count();      
     }  
}
