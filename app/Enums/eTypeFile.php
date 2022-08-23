<?php
namespace App\Enums;
enum eTypeFile: string
{
   case JPG ='jpg'; 
   case JPEG ='jpeg';
   case GIF = 'gif'; 
   case PNG = 'png';
   case PDF = 'pdf';
   case Rar ='Rar'; 
   case PPTx ='PPTx';
   case docx ='docx';
   case dwg ='dwg';
   case Xls ='Xls';
   case Xlsx ='Xlsx';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}