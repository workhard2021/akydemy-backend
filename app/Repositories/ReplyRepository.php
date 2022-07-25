<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Reply;

class ReplyRepository extends RepositoryBase{
    public function __construct(public Reply $model)
    {}
}
