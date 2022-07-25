<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\ModuleUserRepository;

 class ModuleUserService extends ServiceBase{

     public function __construct(public ModuleUserRepository $repos){}
     public function getModel(){
         return $this->repos->model;
     }
 }