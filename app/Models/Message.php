<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{   protected $table='messages';
    protected $fillable=['description','is_active','user_id','topic_id'];
    use HasFactory;
}
