<?php
namespace App\Repositories;
use App\Contracts\RepositoryBase;
use App\Models\Message;
class MessageRepository extends RepositoryBase{
    public function __construct(public Message $model)
    {}
    public function messagesTopic($id){
        //  all messages and replies for topic
        return parent::all();
    }
}
