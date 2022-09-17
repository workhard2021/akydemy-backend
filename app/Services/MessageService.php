<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\MessageRepository;

 class MessageService extends ServiceBase{

     public function __construct(public MessageRepository $repos){}
     public function getModel(){
         return $this->repos->model;
     }
 }