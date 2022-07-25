<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Topic;

class TopicRepository extends RepositoryBase{
    public function __construct(public Topic $model)
    {}
}
