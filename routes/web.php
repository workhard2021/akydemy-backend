<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/test', function () {
//     return view('email.message');
// });
Route::get('/', function () {
    //  return phpinfo() ;
    //  return  ini_get('post_max_size');
     return redirect(config('app.frontend_url'));
    // return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
