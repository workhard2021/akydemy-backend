<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\PageRepository;

 class PageService extends ServiceBase{

     public function __construct(public PageRepository $repos){}
 }