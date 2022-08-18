<?php
return [
   'disk'=> env('DISK','local'),
   'log' => 'public/logo.png',
   'icon'=>'public/icon.png',
   'users'=>env('APP_NAME','akydemy').'/users',
   'publicites'=>env('APP_NAME','akydemy').'/publicites',
   'ressources-modules' => env('APP_NAME','akydemy').'/ressources/modules',
   'ressources-programmes' => env('APP_NAME','akydemy').'/ressources/programmes',
   'countries' => env('APP_NAME','akydemy').'/countries',
   'categories' => env('APP_NAME','akydemy').'/categories',
   'forum' => env('APP_NAME','akydemy').'/forum',
];