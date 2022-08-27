<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{   
    protected $table='replies';
    protected $fillable=['description','is_active','user_id','message_id'];
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
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
}
