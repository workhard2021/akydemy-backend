<?php
namespace App\Enums;
enum eTypeFile: string
{
   case PDF = 'pdf';
   case ZIP = 'zip';
   case RAR ='rar'; 
   case PPTX ='ppTx';
   case DOCX ='docx';
   case DWG ='dwg';
   case XLS ='xls';
   case XLSX ='xlsx';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}