<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Avis extends Model
{   protected $table="avis";
    protected $fillable=[
        'title',
        'email',
        'read',
        'description'
    ];
    use HasFactory,Searchable;
    public function toSearchableArray()
    {    
        return [
        'title' => $this->title,
        'description' => $this->description,
        'email' => $this->email,
       ];
    }
    public function getUpdatedAtAttribute($value)
    {
       return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
    }
    public function getCreatedAtAttribute($value)
    {
        return  $this->updated_at_format=Carbon::parse($value)->locale(config('app.locale'))->calendar();
    }
}
