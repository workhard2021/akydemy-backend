<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\AskEvaluationRepository;

class AskEvaluationService extends ServiceBase{
     public function __construct(public AskEvaluationRepository $repos){}
     public function create($data){
         $data['user_id']=auth()->user()->id;
         if(parent::getModel()->where('user_id',$data['user_id'])->where('module_id',$data['module_id'])->where('accepted',true)->exists()){
                return "rient";
         }
         if(parent::getModel()->where('user_id',$data['user_id'])->where('module_id',$data['module_id'])->exists()){
           return parent::getModel()->where('user_id',$data['user_id'])
           ->where('module_id',$data['module_id'])->update($data);
         }
         return parent::create($data);
     }
}