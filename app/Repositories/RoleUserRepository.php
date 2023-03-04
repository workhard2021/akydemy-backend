<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\RoleUser;

class RoleUserRepository extends RepositoryBase{
    public function __construct(public RoleUser $model)
    {}
}
