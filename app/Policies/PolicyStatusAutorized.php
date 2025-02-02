<?php

namespace App\Policies;

use App\Enums\eRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class PolicyStatusAutorized
{
    use HandlesAuthorization;

  /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {}

    public function super_admin(User $user)
    {    
         return $user->hasRoles([eRole::SUPER_ADMIN->value]) || $this->admin($user);
    }
    public function admin(User $user)
    {
        return $user->hasRoles([eRole::ADMIN->value]);
    }
    public function professeur(User $user)
    {
        return $user->hasRoles([eRole::PROF->value]);
    }
    public function admin_abonnement(User $user)
    {
        return $user->hasRoles([eRole::ADMIN_ABONNEMENT->value]);
    }
    public function studiant(User $user)
    {  

        Log::info($user->roles()->get());
        return $user->hasRoles([eRole::ETUDIANT->value]);
    }
    public function user(User $user)
    {
       return $user->hasRoles([eRole::USER->value]);
    }
}
