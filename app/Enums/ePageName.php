<?php

namespace App\Enums;

enum ePageName: string
{
   case CONDITIONS_GENERALES = 'CONDITIONS GENERALES';
   case CONDITIONS_PAYMENTS = 'CONDITIONS PAYMENTS';
   case APROPOS = 'A PROPOS';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}