<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Programme extends Model
{   protected $model='programmes';
    protected $fillable=['title','sub_title','description','is_active','module_id','module_id'];
    use HasFactory,Searchable;
    public function getUpdatedAtAttribute($value)
    {
        return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
    }
    public function getCreatedAtAttribute($value)
    {
        return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
    }
    public function toSearchableArray()
    {
        return [
         'title' => $this->title,
         'sub_title' => $this->sub_title,
         'description' => $this->description,
       ];
    }
}
