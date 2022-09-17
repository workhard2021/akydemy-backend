<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\ReplyRepository;

class ReplyService extends ServiceBase{

     public function __construct(public ReplyRepository $repos){}
 }