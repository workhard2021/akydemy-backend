<?php
namespace App\Enums;
enum eTypeFile: string
{
   case JPG ='jpg'; 
   case JPEG ='jpeg';
   case GIF = 'gif'; 
   case PNG = 'png';
   case PDF = 'pdf';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}