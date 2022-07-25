<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteStudiant extends Model
{
    protected $table='note_studiants';
    protected $fillable=['note','note_teacher', 'user_id','teacher_id','module_id'];
    use HasFactory;
}
