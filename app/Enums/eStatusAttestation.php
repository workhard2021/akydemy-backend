<?php

namespace App\Enums;

enum eStatusAttestation: string
{
   case ATTENTE = 'ATTENTE';
   case VALIDE = 'VALIDE';
   case INVALIDE = 'INVALIDE';
   case AUCUNE = 'AUCUNE';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}