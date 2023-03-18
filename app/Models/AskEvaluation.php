<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AskEvaluation extends Model
{   protected $table="ask_evaluations";
    use HasFactory;
    protected $fillable=[
        'user_id',
        'module_id',
        'ask',
        'accepted'
    ];

    public function getUpdatedAtAttribute($value)
    {
       return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
    }
    public function getCreatedAtAttribute($value)
    {
        return  $this->created_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
    }
}
