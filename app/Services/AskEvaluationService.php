<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\AskEvaluationRepository;

class AskEvaluationService extends ServiceBase{
     public function __construct(public AskEvaluationRepository $repos){}
     public function create($data){
         $data['user_id']=auth()->user()->id;
         if(parent::getModel()->where('user_id',$data['user_id'])->where('module_id',$data['module_id'])->where('accepted',true)->exists()){
                return true;
         }
         $item=parent::getModel()->where('user_id',$data['user_id'])->where('module_id',$data['module_id'])->first();
         if($item){
            return $item->destroy($item->id);
          }
          $data['ask']=true;
          return  parent::create($data);
     }
}