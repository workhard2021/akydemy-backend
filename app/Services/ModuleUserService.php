<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\ModuleUserRepository;

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
           $item->create($notif);
        }
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
    
        $item=$this->notifService->getModel()
            ->where('event_id',$data->module_id)
            ->where('user_id',$data->user_id)
            ->where('type',$msgActive['type'])
            ->where('is_teacher','!=',true);
        if($data->is_valide){
            $item->create($msgActive);
        }else if($data->is_valide!=true){
             $item->create($msgInactive); 
        }
        //Message d'activation teacter  
        $msgActive['is_teacher']=true;
        $msgActive['description']=__('subscription.teacher-valide-message',
        ['studiant_mail'=>'camara@gmail.com','title'=>$data->title]);
        //Message de la désactivation teacter
        $msgInactive['is_teacher']=true;
        $msgInactive['description']=__('subscription.teacher-invalide-message',['studiant_mail'=>'camara@gmail.com','title'=>$data->title]);
        $item=$this->notifService->getModel()
             ->where('event_id',$data->module_id)
             ->where('user_id',$data->user_id)
             ->where('teacher_id',$data->owner_id)
             ->where('type',$msgInactive['type'])
             ->where('is_teacher',true);
         if($data->is_valide){
             $item->create($msgActive);
         }else if($data->is_valide!=true){
             $item->create($msgInactive); 
         }
         return true;     
    }
 }