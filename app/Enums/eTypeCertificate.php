<?php
namespace App\Enums;
enum eTypeCertificate: string
{
   case DIPLOME ='DIPLÔME'; 
   case CERTIFICAT ='CERTIFICAT';
   case AUCUNE ='AUCUNE';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}