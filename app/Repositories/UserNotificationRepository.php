<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\UserNotification;

class UserNotificationRepository extends RepositoryBase{
    public function __construct(public UserNotification $model)
    {}
}
