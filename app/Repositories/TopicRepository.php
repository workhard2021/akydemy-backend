<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Topic;

class TopicRepository extends RepositoryBase{
    public function __construct(public Topic $model)
    {}

    public function all(){
        return $this->model->with(['messages.replies'])->get();
    }
    public function topicsModule($moduleId,$search){
        return $this->model->where('module_id',$moduleId)->
            when($search!="default",function($query)use($search){
                $query->where(function($query)use($search){
                    $query->where('title','like','%'.$search.'%')
                    ->orWhere('description','like','%'.$search.'%');
                });
            })->withCount('messages')->latest('topics.created_at')->paginate($this->nbr);
    }
    public function findTipic($id){
         return $this->model->where($this->model->getKeyName(),$id)
             ->with('user:id,first_name,last_name,url_file,created_at')?->first();
    }
}
