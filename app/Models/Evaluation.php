<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Evaluation extends Model
{   protected $table="evaluations";
    protected $fillable=['type','title','session_title','visibility_date_limit','published','is_closed','module_id','teacher_id'];
    use HasFactory,Searchable;
    
    public function module(){
        return $this->belongsTo(Module::class,'module_id');
    }
    public function noteStudiants(){
          return $this->hasMany(NoteStudiant::class);
    }
    public function getUpdatedAtAttribute($value)
    {
        return ucfirst(Carbon::parse($value,'UTC')->format('Y-m-d H:i:s'));
       // return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
    }
    public function getCreatedAtAttribute($value)
    {
        // return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
        return ucfirst(Carbon::parse($value,'UTC')->format('Y-m-d H:i:s'));
    }
    public function toSearchableArray()
    {
        return [
        'type' => $this->type,
        'title' => $this->title,
       ];
    }
}
