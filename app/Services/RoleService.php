<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\RoleRepository;

 class RoleService extends ServiceBase{

     public function __construct(public RoleRepository $repos){}
 }