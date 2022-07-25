<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModuleUserController;
use App\Http\Controllers\NoteStudiantController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\RessourcesModuleController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// PUBLIC ROUTES

Route::prefix('/v0.1')->group(function(){
         //NOT AUTH
         Route::prefix('users')->group(function(){
            Route::post('',[UserController::class,'create'])->name('create-users');
            Route::post('login',[UserController::class,'login'])->name('login-users');
            Route::post('callback',[UserController::class,'callback'])->name('callback-users');
            Route::get('redirect',[UserController::class,'redirect'])->name('redirect-users');
         });
        Route::get('modules',[ModuleController::class,'indexPublic'])->name('indexPublic-modules');
        Route::get('programmes',[ProgrammeController::class,'indexPublic'])->name('indexPublic-programmes');
        Route::get('pays',[CountryController::class,'indexPublic'])->name('indexPublic-pays');
        Route::get('/pages/name/{name}',[PageController::class,'showPublic'])->name('showPublic-pages');
        // AUTH
        Route::prefix('/auth')->middleware(['auth:sanctum'])->group(function(){
            Route::prefix('users')->group(function(){
                Route::get('{search?}/{countryId?}/{categorieId?}/{dateBegin?}/{dateEnd?}',[UserController::class,'index'])->name('index-users');
                Route::get('{id}',[UserController::class,'show'])->name('show-users');
                Route::get('current-users/{id}',[UserController::class,'currentUser'])->name('show-current-users');
                Route::put('{id}',[UserController::class,'update'])->name('update-users');
                Route::put('current-users/{id}',[UserController::class,'updateCurrentUser'])->name('update-current-users');
                Route::delete('{id}',[UserController::class,'destroy'])->name('destroy-users');
                Route::delete('current-users',[UserController::class,'deleteCurrentUser'])->name('delete-current-users');       
                Route::get('logout/{id}',[UserController::class,'logout'])->name('logout-users');
                Route::get('/status/teacher',[UserController::class,'userByStatus'])->name('userByStatus-users');
            });
            Route::prefix('categories')->group(function(){
                Route::get('lists',[CategorieController::class,'lists'])->name('lists-categorie');
                Route::get('/{search?}',[CategorieController::class,'index'])->name('index-categorie');
                Route::get('{id}',[CategorieController::class,'show'])->name('show-categorie');
                Route::post('',[CategorieController::class,'create'])->name('create-categorie');
                Route::put('{id}',[CategorieController::class,'update'])->name('update-categorie');
                Route::delete('{id}',[CategorieController::class,'destroy'])->name('destroy-categorie');
            });
            Route::prefix('subscriptions')->group(function(){
                Route::get('/{search?}',[ModuleUserController::class,'index'])->name('index-module-user');
                Route::get('{id}',[ModuleUserController::class,'show'])->name('show-module-user');
                Route::post('',[ModuleUserController::class,'create'])->name('create-module-user');
                Route::put('{id}',[ModuleUserController::class,'update'])->name('update-module-user');
                Route::delete('{id}',[ModuleUserController::class,'destroy'])->name('destroy-module-user');
            });
            Route::prefix('modules')->group(function(){
                Route::get('{search?}',[ModuleController::class,'index'])->name('index-modules');
                Route::get('{id}',[ModuleController::class,'show'])->name('show-modules');
                Route::post('',[ModuleController::class,'create'])->name('create-modules');
                Route::put('{id}',[ModuleController::class,'update'])->name('update-modules');
                Route::delete('{id}',[ModuleController::class,'destroy'])->name('destroy-modules');
            });
            Route::get('ressources/{id?}/module/{search?}',[RessourcesModuleController::class,'ressourceFormModule'])->name('ressourceFormModule-ressources');
            Route::prefix('ressources')->group(function(){
                Route::get('{search?}',[RessourcesModuleController::class,'index'])->name('index-ressources');
                Route::get('{id}',[RessourcesModuleController::class,'show'])->name('show-module-ressources');
                Route::post('',[RessourcesModuleController::class,'create'])->name('create-module-ressources');
                Route::put('{id}',[RessourcesModuleController::class,'update'])->name('update-module-ressources');
                Route::delete('{id}',[RessourcesModuleController::class,'destroy'])->name('destroy-module-ressources');
            });
            Route::prefix('abonnees')->group(function(){
                Route::get('',[CountryController::class,'index'])->name('index-abonnees');
                Route::get('{id}',[CountryController::class,'show'])->name('show-abonnees');
                Route::post('',[CountryController::class,'create'])->name('create-abonnees');
                Route::put('{id}',[CountryController::class,'update'])->name('update-abonnees');
                Route::delete('{id}',[CountryController::class,'destroy'])->name('destroy-abonnees');
            });
            Route::prefix('programmes')->group(function(){
                Route::get('{search?}',[ProgrammeController::class,'index'])->name('index-programmes');
                Route::get('{id}',[ProgrammeController::class,'show'])->name('show-programmes');
                Route::post('',[ProgrammeController::class,'create'])->name('create-programmes');
                Route::put('{id}',[ProgrammeController::class,'update'])->name('update-programmes');
                Route::delete('{id}',[ProgrammeController::class,'destroy'])->name('destroy-programmes');
            });
            Route::prefix('abonnees')->group(function(){
                Route::get('',[CountryController::class,'index'])->name('index-pays');
                Route::get('{id}',[CountryController::class,'show'])->name('show-pays');
                Route::post('',[CountryController::class,'create'])->name('create-pays');
                Route::put('{id}',[CountryController::class,'update'])->name('update-pays');
                Route::delete('{id}',[CountryController::class,'destroy'])->name('destroy-pays');
            });
            Route::prefix('pages')->group(function(){
                Route::get('{search?}',[PageController::class,'index'])->name('index-pages');
                Route::get('{id}',[PageController::class,'show'])->name('show-pages');
                Route::post('',[PageController::class,'create'])->name('create-pages');
                Route::put('{id}',[PageController::class,'update'])->name('update-pages');
                Route::delete('{id}',[PageController::class,'destroy'])->name('destroy-pages');
            });
            Route::prefix('topics')->group(function(){
                Route::get('',[TopicController::class,'index'])->name('index-topics');
                Route::get('{id}',[TopicController::class,'show'])->name('show-topics');
                Route::post('',[TopicController::class,'create'])->name('create-topics');
                Route::put('{id}',[TopicController::class,'update'])->name('update-topics');
                Route::delete('{id}',[TopicController::class,'destroy'])->name('destroy-topics');
            });
            Route::prefix('messages')->group(function(){
                Route::get('',[MessagesController::class,'index'])->name('index-messages');
                Route::get('topic/{id}',[MessagesController::class,'messagesTopic'])->name('messagesTopic-messages');
                Route::get('{id}',[MessagesController::class,'show'])->name('show-messages');
                Route::post('',[MessagesController::class,'create'])->name('create-messages');
                Route::put('{id}',[MessagesController::class,'update'])->name('update-messages');
                Route::delete('{id}',[MessagesController::class,'destroy'])->name('destroy-messages');
            });
            Route::prefix('replies')->group(function(){
                Route::get('',[ReplyController::class,'index'])->name('index-replies');
                Route::get('{id}',[ReplyController::class,'show'])->name('show-replies');
                Route::post('',[ReplyController::class,'create'])->name('create-replies');
                Route::put('{id}',[ReplyController::class,'update'])->name('update-replies');
                Route::delete('{id}',[ReplyController::class,'destroy'])->name('destroy-replies');
            });
            Route::prefix('quiz')->group(function(){
                Route::get('studiant',[QuizController::class,'quizzesStudiant'])->name('quizzesStudiant-index');
                Route::get('/module/{id}',[QuizController::class,'quizzesModule'])->name('quizzesModule-index');
                // clear index after
                Route::get('',[QuizController::class,'index'])->name('index');
                Route::get('{id}',[QuizController::class,'show'])->name('show-quiz');
                Route::post('',[QuizController::class,'create'])->name('create-quiz');
                Route::put('{id}',[QuizController::class,'update'])->name('update-quiz');
                Route::delete('module/{id}',[QuizController::class,'deleteQuizModule'])->name('deleteQuizModule');
                Route::delete('{id}',[QuizController::class,'destroy'])->name('destroy-quiz');
            });
            Route::prefix('note-studiants')->group(function(){
                Route::get('',[NoteStudiantController::class,'index'])->name('index-quiz');
                Route::get('{id}',[NoteStudiantController::class,'show'])->name('show-quiz');
                Route::post('',[NoteStudiantController::class,'create'])->name('create-quiz');
                Route::put('{id}',[NoteStudiantController::class,'update'])->name('update-quiz');
                Route::delete('{id}',[NoteStudiantController::class,'destroy'])->name('destroy-quiz');
            });
        });
});
