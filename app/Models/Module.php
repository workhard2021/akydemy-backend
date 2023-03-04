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
    
    public function categorie(){
        return $this->belongsTo(Module::class);
    }
    public function ask_evaluations(){
        return $this->hasOne(AskEvaluation::class);
    }
    public function subscriptions() {
        return $this->hasMany(ModuleUser::class);
    }
    public function topics() {
        return $this->hasMany(Topic::class);
    }
    public function ressourceModdules(){
        return $this->hasMany(RessourcesModule::class);
    }
    
    public function evaluations(){
        return $this->hasMany(Evaluation::class);
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
        'title' => $this->title,
        'sub_title' => $this->sub_title,
        'description' => $this->description,
        'price' => $this->price,
        'promo_price' => $this->promo_price,
       ];
    }
}
