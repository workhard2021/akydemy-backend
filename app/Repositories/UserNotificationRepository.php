<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\UserNotification;

class UserNotificationRepository extends RepositoryBase{
    public function __construct(public UserNotification $model)
    {}
    public function currentUserNotif(){
        return $this->model->where('user_id',auth()->user()->id)->latest('created_at')->get();
    }
   
}
