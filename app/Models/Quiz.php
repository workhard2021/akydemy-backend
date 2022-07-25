<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{   protected $table='quizzes';
    protected $fillable=['quiz', 'answer','fake_answer','is_active','module_id'];
    use HasFactory;
}
