<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Module extends Model
{   protected $table='modules';
    protected $fillable=['title','sub_title','is_active','promo_price',
            'price','nbr_month','description','owner_id','categorie_id'
    ];
    use HasFactory,Searchable;
    public function users(){
        return $this->belongsToMany(User::class,'module_users');
    }
    public function subscriptions() {
        return $this->hasMany(ModuleUser::class);
    }
    public function topics() {
        return $this->hsMany(Topic::class);
    }
    public function ressourceModdules(){
        return $this->hasMany(RessourcesModule::class);
    }
    
    public function evaluations(){
        return $this->hasMany(Evaluation::class);
    }
    
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
        'price' => $this->price,
        'promo_price' => $this->promo_price
       ];
    }
}
