<?php
return [
   'disk'=> env('DISK','local'),
   'log' => 'public/logo.png',
   'icon'=>'public/icon.png',
   'export'=>env('APP_NAME','akydemy').'/export-file',
   'users'=>env('APP_NAME','akydemy').'/users',
   'examens-evalutions'=>env('APP_NAME','akydemy').'/examens-evalutions',
   'publicites'=>env('APP_NAME','akydemy').'/publicites',
   'modules' => env('APP_NAME','akydemy').'/categories',
   'programmes' => env('APP_NAME','akydemy').'/categories/modules',
   'countries' => env('APP_NAME','akydemy').'/countries',
   'categories' => env('APP_NAME','akydemy').'/categories',
   'forum' => env('APP_NAME','akydemy').'/forum',
];