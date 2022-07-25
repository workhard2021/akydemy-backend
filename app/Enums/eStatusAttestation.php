<?php

namespace App\Enums;

enum eStatusAttestation: string
{
   case ATTENTE = 'ATTENTE';
   case ACCEPTÉ = 'ACCEPTÉ';
   case NONE = 'NONE';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}