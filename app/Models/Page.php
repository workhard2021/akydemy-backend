<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Page extends Model
{  
    protected $model='pages';
    protected $fillable=['name','title','description','user_id','is_active'];
    use HasFactory,Searchable;
    public function getUpdatedAtAttribute($value)
    {
        return ucfirst(Carbon::parse($value,'UTC')->locale(config('app.locale'))->isoFormat('llll'));
    }
    public function getCreatedAtAttribute($value)
    {
        // return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
         return ucfirst(Carbon::parse($value,'UTC')->locale(config('app.locale'))->isoFormat('llll'));
    }
    public function toSearchableArray()
    {
        return [
        'name' => $this->name,
        'title' => $this->title,
        'description'=>$this->description
       ];
    }
}
