<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Categories;

class CategorieRepository extends RepositoryBase{
    public function __construct(public Categories $model)
    {}
}
