<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{   protected $model='user_notifications';
    protected $fillable=['title','type','view_notif','is_teacher','description','user_id','event_id','teacher_id'];
    use HasFactory;
    public function getUpdatedAtAttribute($value)
    {
        return ucfirst(Carbon::parse($value,'UTC')->locale(config('app.locale'))->isoFormat('llll'));
        //return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
    }
    public function getCreatedAtAttribute($value)
    {
       // return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
          return ucfirst(Carbon::parse($value,'UTC')->locale(config('app.locale'))->isoFormat('llll'));
    }
}
