<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Message;
class MessageRepository extends RepositoryBase{
    public function __construct(public Message $model)
    {}
    public function messagesTopic($topicId){
        return $this->model->where('topic_id',$topicId)
         ->with(['replies'=>function($query){
              return $query->with(['user'=>function($query){
                 return $query->where('active',false)
                 ->select('id','first_name','last_name','url_file');
              }])->latest('created_at');
         },'user'=>function($query){
              return $query->where('active',false)
              ->select('id','first_name','last_name','url_file');
         }])->latest('created_at')->paginate($this->nbr);
    }
}
