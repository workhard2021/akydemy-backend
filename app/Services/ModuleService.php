<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\ModuleRepository;

 class ModuleService extends ServiceBase{

     public function __construct(public ModuleRepository $repos){}
 }