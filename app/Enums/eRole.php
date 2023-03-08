<?php

namespace App\Enums;

enum eRole: string
{
   case SUPER_ADMIN ="SUPER ADMIN";
   case ADMIN_PROF ='ADMIN PROF';
   case ADMIN_ABONNEMENT ="ADMIN ABONNEMENT";
   case PROF ='PROFESSEUR';
   case USER ='USER';
   case ETUDIANT ='ETUDIANT';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}
