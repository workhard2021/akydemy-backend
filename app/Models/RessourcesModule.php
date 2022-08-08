<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class RessourcesModule extends Model
{   protected $table='ressources_modules';
    protected $fillable=['title','is_public','is_default','module_id'];
    use HasFactory,Searchable;
    public function module(){
         return $this->hasOne(Module::class,'id','module_id');
    }
    public function getUpdatedAtAttribute($value)
    {
        return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
    }
    public function getCreatedAtAttribute($value)
    {
        return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
        //  return ucfirst(Carbon::parse($value,'UTC')->locale(config('app.locale'))->isoFormat('llll'));
    }
    public function toSearchableArray()
    {
        return[
        'title' => $this->title,
        'module_id' => $this->module_id,
       ];
    }
}
