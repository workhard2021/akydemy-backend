<?php
namespace App\Enums;

enum eStatus: string
{
   case SUPER_ADMIN = 'SUPER ADMIN';
   case ADMIN = 'ADMIN';
   case PROFESSEUR = 'PROFESSEUR';
   case ETUDIANT = 'ETUDIANT';
   case AUTRE = 'AUTRE';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}