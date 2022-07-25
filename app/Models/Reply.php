<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{   
    protected $table='replies';
    protected $fillable=['description','is_active','user_id','message_id'];
    use HasFactory;
}
