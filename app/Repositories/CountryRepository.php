<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Country;

class CountryRepository extends RepositoryBase{
    public function __construct(public Country $model)
    {}
    public function allPublic(){
        return $this->model->all();
    }
}
