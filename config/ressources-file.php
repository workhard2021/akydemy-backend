<?php
return [
   'disk'=> env('DISK','local'),
   'log' => 'public/logo.png',
   'icon'=>'public/icon.png',
   'users'=>env('APP_NAME','tech').'/users',
   'ressources-modules' => env('APP_NAME','tech').'/ressources/modules',
   'ressources-programmes' => env('APP_NAME','tech').'/ressources/programmes',
   'countries' => env('APP_NAME','tech').'/countries',
   'categories' => env('APP_NAME','tech').'/categories',
   'forum' => env('APP_NAME','tech').'/forum',
];