<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{   protected $model='user_notifications';
    protected $fillable=['title','sub_title','description','user_id'];
    use HasFactory;
}
