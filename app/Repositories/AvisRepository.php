<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Avis;

class AvisRepository extends RepositoryBase{
    public function __construct(public Avis $model)
    {}
}
