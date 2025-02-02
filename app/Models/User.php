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
    protected $table="users";
    use HasApiTokens, HasFactory, Notifiable,Searchable;//SoftDeletes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable =[
        'email','first_name','last_name','tel',
        'image_url','active','country','profession','description','password',
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
    public function modulesTeacher(){
        return $this->hasMany(Module::class,'owner_id')
        ->select('modules.owner_id','modules.id','title','sub_title','url_file','name_file','created_at','updated_at');
    }
    
    public function subscriptions() {
        return $this->hasMany(ModuleUser::class);
    }
    public function ask_evaluations(){
        return $this->hasMany(AskEvaluation::class);
    }

    public function cours(){
        return $this->belongsToMany(Module::class,'module_users');
    }

    public function topics(){
        return $this->hasMany(Topic::class);
    }
    public function getUpdatedAtAttribute($value)
    {
        return ucfirst(Carbon::parse($value,'UTC')->format('Y-m-d H:i:s'));
    }
    public function getCreatedAtAttribute($value)
    {
        return ucfirst(Carbon::parse($value,'UTC')->format('Y-m-d H:i:s'));
    }
    public function toSearchableArray()
    {
        return [
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'email' => $this->email,
       ];
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRoles(array $roles){
        return $this->roles()->whereIn('name',$roles)->exists();
    }

    public function is_admin($role){
        return $this->hasRoles($role);
    }
}
