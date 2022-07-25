<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\RessourceModuleRepository;
use App\Repositories\UserNotificationRepository;

 class UserNotificationService extends ServiceBase{
     public function __construct(public UserNotificationRepository $repos){}
     public function getModel(){
         return $this->repos->model;
     }
 }