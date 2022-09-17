<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\TopicRepository;

 class TopicService extends ServiceBase{

     public function __construct(public TopicRepository $repos){}
 }