<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\RessourceModuleRepository;

 class RessourceModuleService extends ServiceBase{

     public function __construct(public RessourceModuleRepository $repos){}
 }