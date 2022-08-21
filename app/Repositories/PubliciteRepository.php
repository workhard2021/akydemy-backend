<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Publicite;

class PubliciteRepository extends RepositoryBase{
    public function __construct(public Publicite $model)
    {}
    public function listNotPaginate(){
        return $this->model->where('is_active',1)
          ->inRandomOrder()->limit(4)
          ->orderBy('updated_at','desc')->get();
    }
}
