<?php

namespace App\Http\Controllers;

use App\Enums\eStatus;
use App\Enums\eStatusAttestation;
use App\Enums\eTypeCertificate;
use App\Enums\eTypeFile;
use App\Libs\ManagerFile;
use App\Services\ModuleUserService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleUserController extends Controller
{
    public function __construct(private ModuleUserService $service,public UserService $userService)
    {}
    public function index(){
        return $this->service->repos->searchText();
    }
    public function create(Request $request){
        $data=$request->validate([
            'module_id'=>'required|numeric',
            'tel'=>'required|string'
        ]);
        $user=auth()->user();
        $data['user_id']=$user->id;
        if($user->status==eStatus::ADMIN->value || $user->status==eStatus::SUPER_ADMIN->value || $user->status==eStatus::PROFESSEUR->value){
            return response(['errors'=>['error'=>"Vous ne pouvez pas effectuer cette opération, veuillez contacter l'administrateur du site. Merci !"]],422);
        }
        $module=$this->service->repos->findModule($data["module_id"]);
        if($this->service->repos->subscriber($data['user_id'],$data['module_id'])){
          return response(['errors'=>['error'=>"Votre abonnement est en cours de traitement, on vous contactera très rapidement pour finaliser le processus. Merci !"]],422);
        }
        if($this->service->repos->moduelExistForUser($data['user_id'],$data['module_id'])){
            return response(['errors'=>['error'=>'Vous êtes déja abonné(e) à ce module. Merci !']],422);
        }
        if($this->service->repos->moduelExistForUserCancel($data['user_id'],$data['module_id'])){
            return response(['errors'=>['error'=>"Votre abonnement a été annulé, veuillez contacter l'administrateur du site. Merci !"]],422);
        }
        if(!$module){
            return response(['errors'=>['error'=>"Ressource existe pas"]],422);
        }
        $module=$this->service->repos->findModule($data["module_id"]);
        $data["module_id"]=$module->id;
        $data["somme"]=$module->promo_price?$module->promo_price:$module->price;
        $data["title"]=$module->title;
        // NOTIFICATION
        $this->userService->update($data['user_id'],['status'=>eStatus::ETUDIANT->value]);
        $item=$this->service->create($data);
        $this->service->createNotication($item);
        return response("Votre demande a été envoyée, l’équipe AKYDEMY vous contactera !",201);
    }

    public function show($id){
        return response($this->service->repos->find($id,['*']),200);
    }
    
    public function attestationsUser(){
         $id=auth()->user()->id;
        return response($this->service->repos->attestationUser($id),200);
    }
    
    public function update(Request $request,$id){
        $data=$request->validate([
            'title'=>'required|string',
            'somme'=>'nullable|numeric',
            'type'=>'required|string|'.Rule::in(eTypeCertificate::getValues()),
            'status_attestation'=>'required|string|'.Rule::in(eStatusAttestation::getValues()),
            'is_valide'=>'nullable|boolean',
            'fichier' => 'nullable|mimes:'.implode(',',eTypeFile::getValues()),
            'description'=>'nullable|string',
            'module_id'=>'required|numeric',
            'user_id'=>'required|numeric',
        ]);

        if(!$this->service->repos->moduelAndUserExist($data['user_id'],$data['module_id'])){
            return response(['errors'=>["error"=>"Utilisateur et module non trouvés"]],422);
        }

        $module=$this->service->repos->findModule($data["module_id"]);
        if(!$module){
            return response(['errors'=>['error'=>'Ressource existe pas']],404);
        }
        $item= $this->service->update($id,$data);
        if($request->hasFile('fichier')){
            $file_name=ManagerFile::genererChaineAleatoire(2).'-'.Carbon::now()->format('Y-m-d').'-'.str_replace(['ô','Ô','é'],['o','o','e','É'],strtolower($item->type));
            $file_name=ManagerFile::upload($data['fichier'],config('ressources-file.users').'/'.$item->user_id.'/attestations',$file_name);
            ManagerFile::delete($item->name_file,config('ressources-file.users').'/'.$item->user_id.'/attestations');
            $item->url_attestation=$file_name['url'];
            $item->name_attestation=$file_name['name'];
            $item->save();
        }
        return response($item,200);
    }

    public function enableOrDesableModuleForUser(Request $request,$id){
        $data=$request->validate([
            'title'=>'required|string',
            'somme'=>'nullable|numeric',
            'type'=>'required|string|'.Rule::in(eTypeCertificate::getValues()),
            'status_attestation'=>'required|string|'.Rule::in(eStatusAttestation::getValues()),
            'is_valide'=>'nullable|boolean',
            'fichier' => 'nullable|mimes:'.implode(',',eTypeFile::getValues()),
            'description'=>'nullable|string',
            'module_id'=>'required|numeric',
            'user_id'=>'required|numeric',
        ]);

        if(!$this->service->repos->moduelAndUserExist($data['user_id'],$data['module_id'])){
            return response(['errors'=>["error"=>"Utilisateur et module non trouvés"]],422);
        }
        $module=$this->service->repos->findModule($data["module_id"]);
        if(!$module){
            return response(['errors'=>['error'=>'Ressource existe pas']],404);
        }

        $item= $this->service->update($id,$data);
        if($request->hasFile('fichier')){
            $file_name=ManagerFile::genererChaineAleatoire(2).'-'.Carbon::now()->format('Y-m-d').'-'.str_replace(['ô','Ô','é'],['o','o','e','É'],strtolower($item->type));
            $file_name=ManagerFile::upload($data['fichier'],config('ressources-file.users').'/'.$item->user_id.'/attestations',$file_name);
            ManagerFile::delete($item->name_file,config('ressources-file.users').'/'.$item->user_id.'/attestations');
            $item->url_attestation=$file_name['url'];
            $item->name_attestation=$file_name['name'];
            $item->save();
        }

        $item->owner_id=$module->owner_id;
        $this->service->createNoticationForTeacherAndStudiant($item);
        return response($item,200);
    }
    
    public function destroy($id){
        $item=$this->service->repos->find($id);
        if($item && $item->url_attestation){
            ManagerFile::deleteWithUrl($item->url_attestation);
        }
        return response($this->service->delete($id),204);
    }
}
