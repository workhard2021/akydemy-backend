<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Enums\eRole;
use App\Events\FeedbackEvent;
use App\Mail\UserNotificationSubscriptionMail;
use App\Models\User;
use App\Repositories\ModuleUserRepository;
use Illuminate\Support\Facades\Mail;
use PHPUnit\TextUI\XmlConfiguration\Group;

 class ModuleUserService extends ServiceBase{

     public function __construct(public ModuleUserRepository $repos,private UserNotificationService $notifService){}
     public function getModel(){
         return $this->repos->model;
     }
     public function createNotication($data){
        $notif=[];
        $notif['event_id']=$data->module_id;
        $notif['user_id']=$data->user_id;
        $notif['type']=__('subscription.type',['type'=>'Abonnement']);
        $notif['title']=__('subscription.title',['title'=>$data->title]);
        $notif['description']=__('subscription.message');
        $item=$this->notifService->getModel()
            ->where('event_id',$data->module_id)
            ->where('user_id',$data->user_id)
            ->where('type',$data['type']);
        if($item->exists()){
          $item->update($notif);
        }else{
           $notif['view_notif']=false;
           $item->create($notif);
        }
        $exp_studiant=User::find($notif['user_id']);
        $user=User::get();
        $mailAdmin=[];
        foreach($user as $admin){
            if(!in_array($admin->email,$mailAdmin)){
                if(in_array(eRole::ADMIN_ABONNEMENT->value,$admin->roles()->pluck('name')->toArray()) || in_array(eRole::SUPER_ADMIN->value,$admin->roles()->pluck('name')->toArray())){
                    $mailAdmin=[...$mailAdmin,$admin->email];
                    $name=ucfirst(strtolower($admin->first_name." ".$admin->last_name));
                    $notif['title']=__('subscription.title_admin',['title'=>$data->title]);
                    $notif['description']=__('subscription.message_admin',['name'=>$name]);
                    Mail::to($admin)->send(new UserNotificationSubscriptionMail($notif));
                }
            }
        }
        Mail::to($exp_studiant)->send(new UserNotificationSubscriptionMail($notif));
        return $item;     
    }
    public function createNoticationForTeacherAndStudiant($data){
        //Message d'activation statudiant  
        $msgActive=[];
        $msgActive['event_id']=$data->module_id;
        $msgActive['user_id']=$data->user_id;
        $msgActive['teacher_id']=$data->owner_id;
        $msgActive['type']=__('subscription.type',['type'=>'Abonnement']);

        $msgActive['title']=__('subscription.valide-title');
        $msgActive['description']=__('subscription.studiant-valide-message',['title'=>$data->title]);
        //Message de la desactivation statudiant
        $msgInactive=[];
        $msgInactive['type']=__('subscription.type',['type'=>'Désabonnement']);
        $msgInactive['event_id']=$data->module_id;
        $msgInactive['user_id']=$data->user_id;
        $msgInactive['teacher_id']=$data->owner_id;
        $msgInactive['title']=__('subscription.invalide-title');
        $msgInactive['description']=__('subscription.studiant-invalide-message',['title'=>$data->title]);
        $exp_studiant=User::find($msgInactive['user_id']);
        $exp_techer=User::find($msgInactive['teacher_id']);
        $item=$this->notifService->getModel()
            ->where('event_id',$data->module_id)
            ->where('user_id',$data->user_id)
            ->where('type',$msgActive['type'])
            ->where('is_teacher','!=',true);
        if($data->is_valide){
             $msgActive['view_notif']=false;
             $item->create($msgActive);
             Mail::to($exp_studiant)->send(new UserNotificationSubscriptionMail($msgActive));
        }else if($data->is_valide!=true){
             $msgInactive['view_notif']=false;
             $item->create($msgInactive); 
             Mail::to($exp_studiant)->send(new UserNotificationSubscriptionMail($msgInactive));
        }
        //Message d'activation teacter  
        $msgActive['is_teacher']=true;
        $msgActive['description']=__('subscription.teacher-valide-message',
        ['studiant_mail'=>$exp_studiant->email,'title'=>$data->title]);
        //Message de la désactivation teacter
        $msgInactive['is_teacher']=true;
        $msgInactive['description']=__('subscription.teacher-invalide-message',['studiant_mail'=>$exp_studiant->email,'title'=>$data->title]);
        $item=$this->notifService->getModel()
             ->where('event_id',$data->module_id)
             ->where('user_id',$data->user_id)
             ->where('teacher_id',$data->owner_id)
             ->where('type',$msgInactive['type'])
             ->where('is_teacher',true);
         if($data->is_valide){
             $msgActive['view_notif']=false;
             $item->create($msgActive);
             Mail::to($exp_techer)->send(new UserNotificationSubscriptionMail($msgActive));
         }else if($data->is_valide!=true){
             $msgInactive['view_notif']=false;
             $item->create($msgInactive); 
             Mail::to($exp_techer)->send(new UserNotificationSubscriptionMail($msgInactive));
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
 }