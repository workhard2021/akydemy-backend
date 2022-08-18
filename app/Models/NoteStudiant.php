<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteStudiant extends Model
{
    protected $table='note_studiants';
    protected $fillable=['type','title','url_file','name_file','note','note_teacher','is_closed','user_id','evaluation_id','teacher_id','module_id'];
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function module(){
        return $this->belongsTo(Module::class);
    }
    public function teacher(){
      return $this->belongsTo(User::class,'teacher_id');
    }
    public function getUpdatedAtAttribute($value)
    {
       // return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
          return ucfirst(Carbon::parse($value,'UTC')->locale(config('app.locale'))->isoFormat('llll'));
    }
    public function getCreatedAtAttribute($value)
    {
       // return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
       return ucfirst(Carbon::parse($value,'UTC')->locale(config('app.locale'))->isoFormat('llll'));
    }
}
