<?php

namespace App\Enums;

enum eTypeEvaluation: string
{
   case EXAMEN = 'EXAMEN';
   case EVALUATION = 'EVALUATION';
   case AUTRE = 'AUTRE';
   public static function getValues(): array
   {
      return array_map(function ($val) {
         return $val->value;
      }, self::cases());
   }
}