<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Topic extends Model
{
    protected $table='topics';
    protected $fillable=['title','description','is_active','user_id','module_id'];
    use HasFactory;
}
