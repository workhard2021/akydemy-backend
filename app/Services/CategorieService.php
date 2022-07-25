<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\CategorieRepository;

 class CategorieService extends ServiceBase{

     public function __construct(public CategorieRepository $repos){}
 }