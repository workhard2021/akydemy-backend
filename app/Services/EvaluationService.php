<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\EvaluationRepository;

 class EvaluationService extends ServiceBase{

     public function __construct(public EvaluationRepository $repos){}
 }