<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Enums\eRole;
use App\Events\FeedbackEvent;
use App\Mail\UserNotificationSubscriptionMail;
use App\Models\User;
use App\Repositories\ModuleUserRepository;
use Illuminate\Support\Facades\Mail;

 class ModuleUserService extends ServiceBase{

     public function __construct(public ModuleUserRepository $repos,private UserNotificationService $notifService,private UserService $userService){}
     public function getModel(){
         return $this->repos->model;
     }
     public function createNotication($data){
        $notif=[];
        $notif['event_id']=$data->module_id;
        $exp_studiant=User::find($data->user_id);
        $name_studiant=ucfirst(strtolower($exp_studiant->first_name." ".$exp_studiant->last_name));
        $notif['user_id']=$data->user_id;
        $notif['type']=__('subscription.type',['type'=>'Abonnement']);
        $user=User::get();
        $mailAdmin=[];
        foreach($user as $admin){
            if(!in_array($admin->email,$mailAdmin)){
                if(in_array(eRole::ADMIN_ABONNEMENT->value,$admin->roles()->pluck('name')->toArray()) || in_array(eRole::SUPER_ADMIN->value,$admin->roles()->pluck('name')->toArray())){
                    $mailAdmin=[...$mailAdmin,$admin->email];
                    $name=ucfirst(strtolower($admin->first_name." ".$admin->last_name));
                    $notif['title']=__('subscription.title_admin',['title'=>$data->title]);
                    $notif['description']=__('subscription.message_admin',['name'=>$name]);
                    $notif['user_id']=$admin->id;
                    $item=$this->notifService->getModel()
                       ->where('event_id',$data->module_id)
                       ->where('user_id',$admin->id)
                        ->where('type',$data['type']);
                     if($item->exists()){
                         $item->update($notif);
                     }else{
                         $notif['view_notif']=false;
                         $item->create($notif);
                    }
                    Mail::to($admin)->send(new UserNotificationSubscriptionMail($notif));
                }
            }
        }
        $notif['title']=__('subscription.title',['title'=>$data->title]);
        $notif['description']=__('subscription.message',['name'=>$name_studiant]);
        $item=$this->notifService->getModel()
        ->where('event_id',$data->module_id)
        ->where('user_id',$data->user_id)
        ->where('type',$data['type']);
        $notif['user_id']=$data->user_id;
        if($item->exists()){
           $item->update($notif);
        }else{
            $notif['view_notif']=false;
            $item->create($notif);
        }
        Mail::to($exp_studiant)->send(new UserNotificationSubscriptionMail($notif));
        return $item;     
    }
    public function createNoticationForTeacherAndStudiant($data){
        $exp_studiant=User::find($data->user_id);
        $exp_teacher=User::find($data->owner_id);
        $name_studiant=ucfirst(strtolower($exp_studiant->first_name." ".$exp_studiant->last_name));
        $name_teacher=ucfirst(strtolower($exp_teacher->first_name." ".$exp_teacher->last_name));
        //Message d'activation statudiant  
        $msgActive=[];
        $msgActive['type']=__('subscription.type',['type'=>'Abonnement']);
        $msgActive['title']=__('subscription.valide-title');
        $msgActive['description']=__('subscription.studiant-valide-message',['title'=>$data->title,'name'=>$name_studiant]);
        //Message de la desactivation statudiant
        $msgInactive=[];
        $msgInactive['type']=__('subscription.type',['type'=>'Désabonnement']);
        $msgInactive['title']=__('subscription.invalide-title');
        $msgInactive['description']=__('subscription.studiant-invalide-message',['title'=>$data->title,'name'=>$name_studiant]);

        if($data->is_valide){
             $msgActive['view_notif']=false;
             $this->notifService->create([...$msgActive,'event_id'=>$data->module_id,'user_id'=>$data->user_id]);
             Mail::to($exp_studiant)->send(new UserNotificationSubscriptionMail($msgActive));
        }else if($data->is_valide!=true){
             $msgInactive['view_notif']=false;
             $this->notifService->create([...$msgInactive,'event_id'=>$data->module_id,'user_id'=>$data->user_id]);
             Mail::to($exp_studiant)->send(new UserNotificationSubscriptionMail($msgInactive));
        }
        //Message d'activation teacter  
        $msgActive['description']=__('subscription.teacher-valide-message',['studiant_mail'=>$exp_studiant->email,'title'=>$data->title,'name'=>$name_teacher]);
        //Message de la désactivation teacter
        $msgInactive['description']=__('subscription.teacher-invalide-message',['studiant_mail'=>$exp_studiant->email,'title'=>$data->title,'name'=>$name_teacher]);
        if($data->is_valide){
             $msgActive['view_notif']=false;
             $this->notifService->create([...$msgActive,'event_id'=>$data->module_id,'user_id'=>$data->owner_id]);
             Mail::to($exp_teacher)->send(new UserNotificationSubscriptionMail($msgActive));
         }else if($data->is_valide!=true){
             $msgInactive['view_notif']=false;
             $this->notifService->create([...$msgInactive,'event_id'=>$data->module_id,'user_id'=>$data->owner_id]);
             Mail::to($exp_teacher)->send(new UserNotificationSubscriptionMail($msgInactive));
         }
         return true;     
    }
    
    public function emailSuscription($data){
        if(request()->has('emails')){
            $data["emails"]=explode(',',request()->emails);
         }elseif(request()->has('all_user') && request()->all_user){
            $data['emails']=$this->repos->model->where("is_valide",true)
            ->distinct("email")->join('users','users.id','=','module_users.user_id')->get(['email'])->toArray();
         }else{
            $data['emails']=[];
         }
         FeedbackEvent::dispatch($data);
    }
    public function moduelExistForUserUpdate($data){
        $item=$this->repos->model->where([
               ['user_id','=',$data['user_id']],
               ['module_id','=',$data['module_id']],
               ['is_valide','=',false]
        ])->first();
        if($item){
            $module=$this->repos->findModule($data['module_id']);
            $module->module_id=$module->id;
            $module->owner_id=$module->owner_id;
            $module->user_id=$data['user_id'];
            $module->tel=$data['tel'];
            $module->somme=$module->promo_price?$module->promo_price:$module->price;
            $module->title=$module->title;
            $item->save();
            //$this->userService->updateUserRole($data['user_id']);
            $this->createNotication($module);
        }
        return $item;  
    }
    public function create($data){
        $module=$this->repos->findModule($data["module_id"]);
        $data["module_id"]=$module->id;
        $data["somme"]=$module->promo_price?$module->promo_price:$module->price;
        $data["title"]=$module->title;
        $item=$this->repos->model->create($data);
        // NOTIFICATION
       //s $this->userService->updateUserRole($data['user_id']);
        $item->user_id=$data['user_id'];
        $this->createNotication($item);
    }
 }