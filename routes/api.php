<?php

use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ManagerFileController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModuleUserController;
use App\Http\Controllers\NoteStudiantController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\PubliciteController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\RessourcesModuleController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserNotificationController;
use App\Mail\UserNotificationSubscriptionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
Route::prefix(config('app.version'))->group(function(){
         //NOT AUTH
         Route::post('/forgot-password',[PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');
         Route::post('/reset-password',[NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');
        // Route::post('test-video',function(Request $request){
        //       $data=$request->validate(['fichier'=>'nullable']);
        //     //   return $data;
        //         $name='test.'.$data['fichier']->getClientOriginalExtension();
        //         // return $name;
        //         $data=$data['fichier']?->storeAs('test',$name,'s3');
        //         $data=Storage::disk('s3')->url('akydemy/categories/2/module/2/video/26SqxInywo.mp4');
        //         return $data;    
        // });
        //  Route::get('/test',function(){
        //        //Mail::to("patronp60@gmail.com")->send(new UserNotificationSubscriptionMail(['title'="test title","description"=>""]));
        //        return "test";
        //  });
         Route::prefix(config('app.name'))->group(function(){
               Route::get('examens-evalutions/{Id}/{Date}/{url_file}',[ManagerFileController::class,'download']);
               // Route::get('/ressources/modules/{Id}/EVALUATION/{Date}/{url_file}',[ManagerFileController::class,'download']);
               Route::get('public/{url_file}',[ManagerFileController::class,'download']);
               Route::get('/categories/{Id}/{url_file}',[ManagerFileController::class,'download']);
               Route::get('/categories/{Id_categorie}/module/{Id}/programme/{url_file}',[ManagerFileController::class,'download']);
               Route::get('/categories/{Id_categorie}/module/{Id}/pdf/{url_file}',[ManagerFileController::class,'download']);
               Route::get('/categories/{Id_categorie}/module/{Id}/{url_file}',[ManagerFileController::class,'download']);
               Route::get('/categories/{Id_categorie}/module/{Id}/video/{url_file}',[ManagerFileController::class,'download']);
               Route::get('/users/{Id}/profile/{url_file}',[ManagerFileController::class,'download']);
               Route::get('/users/{Id}/attestations/{url_file}',[ManagerFileController::class,'download']); 
               Route::get('/publicites/images/{url_file}',[ManagerFileController::class,'download']);
               Route::get('/users/{Id}/{moduleId}/{type}/{date}/{url_file}',[ManagerFileController::class,'download']);
        });
        Route::prefix('users')->group(function(){
            Route::get('/status/professeur',[UserController::class,'userByStatusProf'])->name('userByStatusProf-users');
            Route::post('',[UserController::class,'create'])->name('create-users');
            Route::post('login',[UserController::class,'login'])->name('login-users');
            Route::post('reset-password',[UserController::class,'resetPassword'])->name('reset-password-users');
            Route::post('callback',[UserController::class,'callback'])->name('callback-users');
            Route::get('redirect',[UserController::class,'redirect'])->name('redirect-users');
        });
        Route::get('topics/module/{moduleId?}/{search?}',[TopicController::class,'topicsModule'])->name('topicsModule-public-modules');
        Route::get('/messages/topic/{topicId}',[MessagesController::class,'messagesTopic'])->name('messagesTopic-public-modules');
        Route::get('topics/{id}',[TopicController::class,'show'])->name('show-public-topics');
        
        Route::get('modules',[ModuleController::class,'listNotPaginate'])->name('listNotPaginate-modules');
        Route::get('modules/public/{search?}',[ModuleController::class,'indexPublic'])->name('indexPublic-modules');
        Route::get('modules/{id}',[ModuleController::class,'show'])->name('show-public-modules');
        Route::get('modules/{id}/ressources',[ModuleController::class,'showModuleRessource'])->name('showModuleRessource-modules');
        Route::get('programmes/public/{search?}',[ProgrammeController::class,'indexPublic'])->name('indexPublic-programmes');
        Route::get('programmes/{id}',[ProgrammeController::class,'show'])->name('showPublic-programmes');
        Route::get('pays',[CountryController::class,'indexPublic'])->name('indexPublic-pays');
        Route::get('/pages/name/{name}',[PageController::class,'showPublic'])->name('showPublic-pages');
        Route::get('publicites',[PubliciteController::class,'lists'])->name('lists-public-publicites');
       
        Route::prefix('/auth')->middleware(['auth:sanctum'])->group(function(){
            // Route::post('/send-mail', function (Request $request) {
            //      $msg=$request->input('message');
            //      Mail::to($request->user())->send(new UserNotificationSubscriptionMail($msg));
            //      return "mail sended";
            // });
            Route::prefix('notifs')->group(function(){
                Route::get('current/user',[UserNotificationController::class,'currentUserNotif'])->name('currentUserNotif');
                Route::get('current/teacher',[UserNotificationController::class,'currentTecherNotif'])->name('currentTecherNotif');
                Route::get('current/user/not/read',[UserNotificationController::class,'currentUserNoteNotRead'])->name('currentUserNoteNotRead');
                Route::put('current/user/{id}',[UserNotificationController::class,'update'])->name('currentUserNotif-update');
            });
            Route::get('current-users/modules/evaluations',[UserController::class,'currentUserEvaluationModule'])->name('currentUserEvaluationModule-module');
            Route::get('current-users/evaluations/{moduleId}',[UserController::class,'currentUserEvaluations'])->name('currentUserEvaluations-evaluation');
            Route::get('current-users/notes',[UserController::class,'currentUserNotes'])->name('currentUserNotes-notes');
            
            Route::prefix('users')->group(function(){
                Route::get('{id}',[UserController::class,'show'])->name('show-users');
                Route::get('current/users/module',[UserController::class,'currentUserModule'])->name('currentUserModules');
                Route::get('current-users/{id}',[UserController::class,'currentUser'])->name('show-current-users');
                Route::put('{id}',[UserController::class,'update'])->name('update-users');
                Route::post('current-users/{id}',[UserController::class,'updateCurrentUser'])->name('update-current-users');
                Route::delete('{id}',[UserController::class,'destroy'])->name('destroy-users');
                Route::delete('current-users',[UserController::class,'deleteCurrentUser'])->name('delete-current-users');       
                Route::get('logout/{id}',[UserController::class,'logout'])->name('logout-users');
                Route::get('all/{search?}/{country?}/{dateBegin?}/{dateEnd?}',[UserController::class,'allUsers'])->name('allUsers-users');
                Route::get('{search?}/{country?}/{categorieId?}/{moduleId?}/{is_valide?}/{dateBegin?}/{dateEnd?}',[UserController::class,'index'])->name('index-users');
            });
            Route::prefix('categories')->group(function(){
                Route::get('list/no-paginate',[CategorieController::class,'listNotPaginate'])->name('categories-listNotPaginate');
                Route::get('/{search?}',[CategorieController::class,'index'])->name('index-categorie');
                Route::get('{id}',[CategorieController::class,'show'])->name('show-categorie');
                Route::post('',[CategorieController::class,'create'])->name('create-categorie');
                Route::post('{id}',[CategorieController::class,'update'])->name('update-categorie');
                Route::delete('{id}',[CategorieController::class,'destroy'])->name('destroy-categorie');
            });
            Route::prefix('publicites')->group(function(){
                // Route::get('{id}',[PubliciteController::class,'show'])->name('show-publicite');
                Route::post('',[PubliciteController::class,'create'])->name('create-publicite');
                Route::post('{id}',[PubliciteController::class,'update'])->name('update-publicite');
                Route::delete('{id}',[PubliciteController::class,'destroy'])->name('destroy-publicite');
                Route::get('/{search?}',[PubliciteController::class,'index'])->name('index-publicite');
            });
            Route::prefix('attestations')->group(function(){
                Route::get('{id}',[ModuleUserController::class,'show'])->name('show-module-user');
                Route::get('current/user',[ModuleUserController::class,'attestationsUser'])->name('attestationsUser-module-user');
            });
            
            Route::prefix('subscriptions')->group(function(){
                Route::get('/{search?}',[ModuleUserController::class,'index'])->name('index-module-user');
                Route::post('',[ModuleUserController::class,'create'])->name('create-module-user');
                Route::post('/enable-desable-module-user/{id}',[ModuleUserController::class,'enableOrDesableModuleForUser'])->name('enableOrDesableModuleForUser-module-user');
                Route::post('{id}',[ModuleUserController::class,'update'])->name('update-module-user');
                Route::delete('{id}',[ModuleUserController::class,'destroy'])->name('destroy-module-user');
            });
            Route::prefix('modules')->group(function(){
                Route::get('list-no-paginate',[ModuleController::class,'listNotPaginate'])->name('modules-listNotPaginate');
                Route::get('for/examens/{search?}',[ModuleController::class,'moduleforExams'])->name('moduleforExams-index');
                Route::get('{search?}',[ModuleController::class,'index'])->name('index-modules');
                Route::get('{id}',[ModuleController::class,'show'])->name('show-modules');
                Route::post('',[ModuleController::class,'create'])->name('create-modules');
                Route::post('{id}',[ModuleController::class,'update'])->name('update-modules');
                Route::delete('{id}',[ModuleController::class,'destroy'])->name('destroy-modules');
            });
            Route::get('ressources/studiant/{search?}',[RessourcesModuleController::class,'searchResourceModuleStudiant'])->name('searchResourceModuleStudiant-ressources');
            Route::get('ressources/{id?}/module/{search?}',[RessourcesModuleController::class,'ressourceFormModule'])->name('ressourceFormModule-ressources');
            Route::prefix('ressources')->group(function(){
                Route::get('admin/{search?}',[RessourcesModuleController::class,'searchResourceModuleAdmin'])->name('searchResourceModuleAdmin-ressources');
                Route::get('{search?}',[RessourcesModuleController::class,'index'])->name('index-ressources');
                Route::get('{id}',[RessourcesModuleController::class,'show'])->name('show-module-ressources');
                Route::post('',[RessourcesModuleController::class,'create'])->name('create-module-ressources');
                Route::post('{id}',[RessourcesModuleController::class,'update'])->name('update-module-ressources');
                Route::delete('{id}',[RessourcesModuleController::class,'destroy'])->name('destroy-module-ressources');
            });
            Route::prefix('programmes')->group(function(){
                Route::get('{search?}',[ProgrammeController::class,'index'])->name('index-programmes');
                Route::get('{id}',[ProgrammeController::class,'show'])->name('show-programmes');
                Route::post('',[ProgrammeController::class,'create'])->name('create-programmes');
                Route::post('{id}',[ProgrammeController::class,'update'])->name('update-programmes');
                Route::delete('{id}',[ProgrammeController::class,'destroy'])->name('destroy-programmes');
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
                Route::post('{id}',[TopicController::class,'update'])->name('update-topics');
                Route::delete('{id}',[TopicController::class,'destroy'])->name('destroy-topics');
            });
            Route::prefix('messages')->group(function(){
                Route::get('',[MessagesController::class,'index'])->name('index-messages');
                Route::get('topic/{id}',[MessagesController::class,'messagesTopic'])->name('messagesTopic-messages');
                Route::get('{id}',[MessagesController::class,'show'])->name('show-messages');
                Route::post('',[MessagesController::class,'create'])->name('create-messages');
                Route::post('{id}',[MessagesController::class,'update'])->name('update-messages');
                Route::delete('{id}',[MessagesController::class,'destroy'])->name('destroy-messages');
            });
            Route::prefix('replies')->group(function(){
                Route::get('',[ReplyController::class,'index'])->name('index-replies');
                Route::get('{id}',[ReplyController::class,'show'])->name('show-replies');
                Route::post('',[ReplyController::class,'create'])->name('create-replies');
                Route::post('{id}',[ReplyController::class,'update'])->name('update-replies');
                Route::delete('{id}',[ReplyController::class,'destroy'])->name('destroy-replies');
            });
            // Route::prefix('quiz')->group(function(){
            //     Route::get('studiant',[QuizController::class,'quizzesStudiant'])->name('quizzesStudiant-index');
            //     Route::get('/module/{id}',[QuizController::class,'quizzesModule'])->name('quizzesModule-index');
            //     // clear index after
            //     Route::get('',[QuizController::class,'index'])->name('index');
            //     Route::get('{id}',[QuizController::class,'show'])->name('show-quiz');
            //     Route::post('',[QuizController::class,'create'])->name('create-quiz');
            //     Route::put('{id}',[QuizController::class,'update'])->name('update-quiz');
            //     Route::delete('module/{id}',[QuizController::class,'deleteQuizModule'])->name('deleteQuizModule');
            //     Route::delete('{id}',[QuizController::class,'destroy'])->name('destroy-quiz');
            // });
            Route::prefix('evaluations')->group(function(){
                Route::get('module/{moduleId}',[EvaluationController::class,'getEvaluationForModuleId'])->name('moduleforExams-index-evaluation');
                Route::get('{search?}',[EvaluationController::class,'index'])->name('index-evaluation');
                Route::get('/show/{id}',[EvaluationController::class,'show'])->name('show-evaluation');
                Route::post('',[EvaluationController::class,'create'])->name('create-evaluation');
                Route::put('{id}',[EvaluationController::class,'update'])->name('update-evaluation');
                Route::delete('{id}',[EvaluationController::class,'destroy'])->name('destroy-evaluation');
            });

            
            Route::prefix('note-studiants')->group(function(){
                Route::get('result/{search?}/{dateBegin?}/{dateEnd?}',[NoteStudiantController::class,'noteStudiantWithInfo'])->name('noteStudiantWithInfo-search-studiant');
                Route::get('current/users-notes/{moduleId}',[NoteStudiantController::class,'currentUserNote'])->name('currentUserNote-studiant');
                Route::get('',[NoteStudiantController::class,'index'])->name('index-notes-studiant');
                Route::get('{id}',[NoteStudiantController::class,'show'])->name('show-notes-studiant');
                Route::post('',[NoteStudiantController::class,'create'])->name('create-notes-studiant');
                Route::put('{id}',[NoteStudiantController::class,'update'])->name('update-notes-studiant');
                Route::delete('{id}',[NoteStudiantController::class,'destroy'])->name('destroy-notes-studiant');
            });
        });
});
