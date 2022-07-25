<?php

namespace App\Enums;

enum eModuleValidate: string
{
   case ATTENTE = 'ATTENTE';
   case RÉJÉTÉ = 'RÉJÉTÉ';
   case ACCEPTÉ = 'ACCEPTÉ';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}