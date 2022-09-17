<?php

namespace App\Enums;

enum ePageName: string
{

   case APROPOS = "A PROPOS";
   case CONDITIONS_GENERALES = 'CONDITIONS GENERALES';
   case CONDITIONS_PAYMENTS = 'CONDITIONS PAYMENTS';
   case METHODOLOGY = "MÉTHODOLOGY";
   case DOMAINES_FORMATIONS = "DOMAINES DE FORMATIONS";
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}