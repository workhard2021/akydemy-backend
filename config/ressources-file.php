<?php
return [
   'disk'=> env('DISK','local'),
   'presentation-log' => 'presentation-log.png',
   'icon'=>'image/icon.png',
   'user'=>env('PREFIX_COMP','tech-log').'/users/image',
   'attestation' => env('PREFIX_COMP', 'tech-log') .'/users/attestation',
   'movie_module' => env('PREFIX_COMP','tech-log').'/movie-module',
   'file_module' => env('PREFIX_COMP','tech-log').'/file-module',
   'country' => env('PREFIX_COMP','tech-log').'/country',
   'categorie_module' => env('PREFIX_COMP','tech-log').'/categorie-module',
   'file_message' => env('PREFIX_COMP','tech-log').'/file-message',
];