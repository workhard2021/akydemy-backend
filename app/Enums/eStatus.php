<?php
namespace App\Enums;

enum eStatus: string
{
   case TEACHER = 'TEACHER';
   case ADMIN = 'ADMIN';
   case STUDIENT = 'STUDIENT';
   case OTHER = 'OTHER';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}