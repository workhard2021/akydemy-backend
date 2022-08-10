<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleUser extends Model
{   protected $table='module_users';
    protected $fillable=['title','somme','type','status_attestation','description','is_valide','module_id','user_id'];
    use HasFactory;
}
