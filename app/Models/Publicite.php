<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Publicite extends Model
{   protected $table='publicites';
    protected $fillable=['name','title','is_active'];
    use HasFactory,Searchable;

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
        return [
        'name' => $this->name,
        'title' => $this->title,
       ];
    }
}
