<?php

namespace App\Enums;
enum eSymbolChange: string
{
   case GNF = 'GNF';
   case MAD = 'MAD';
   case FCFA = 'FCFA';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}