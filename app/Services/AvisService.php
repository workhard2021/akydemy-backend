<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\AvisRepository;

class AvisService extends ServiceBase{
     public function __construct(public AvisRepository $repos){}
}