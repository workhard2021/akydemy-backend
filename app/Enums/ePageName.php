<?php

namespace App\Enums;

enum ePageName: string
{
   case METHODOLOGY = 'METHODOLOGY';
   case FORMATION = 'FORMATION';
   case APROPOS = 'A PROPOS';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}