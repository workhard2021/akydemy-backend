<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\PubliciteRepository;

 class PubliciteService extends ServiceBase{
     public function __construct(public PubliciteRepository $repos){}
 }