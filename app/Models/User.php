<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes,Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable =[
        'email','first_name','last_name','tel',
        'image_url','status','active','country','profession','description','password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function modules(){
        return $this->belongsToMany(Module::class,'module_users')
        ->as('subscriptions')
        ->withPivot(['id','title','country','somme','type','status_attestation','is_valide','url_attestation','name_attestation'])
        ->withTimestamps();
    }
    
    public function subscriptions() {
        return $this->hasMany(ModuleUser::class);
    }

    public function cours(){
        return $this->belongsToMany(Module::class,'module_users');
        // ->as('subscriptions');
        // ->withPivot(['id','title','country','somme','type','status_attestation','is_valide','url_attestation','name_attestation'])
    }

    public function topics(){
        return $this->hasMany(Topic::class);
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
        return [
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'email' => $this->email,
       ];
    }
}
