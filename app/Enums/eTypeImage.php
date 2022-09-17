<?php
namespace App\Enums;
enum eTypeImage: string
{
   case JPG ='jpg'; 
   case JPEG ='jpeg';
   case GIF = 'gif'; 
   case PNG = 'png';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}