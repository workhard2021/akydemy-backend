<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Page;

class PageRepository extends RepositoryBase{
    public function __construct(public Page $model)
    {}
    public function showPublic($name){
        return $this->model->where('is_active',1)
                 ->where('name',$name)->first();
    }
}
