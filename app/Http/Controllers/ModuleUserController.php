<?php

namespace App\Http\Controllers;

use App\Enums\eStatusAttestation;
use App\Enums\eTypeCertificate;
use App\Libs\ManagerFile;
use App\Services\ModuleUserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleUserController extends Controller
{
    public function __construct(private ModuleUserService $service)
    {}
    public function index(){
        return $this->service->repos->searchText();
    }
    public function create(Request $request){
        $data=$request->validate([
            'module_id'=>'required|numeric',
            'tel'=>'required|string'
        ]);
        $data['user_id']=auth()->user()->id;
        $module=$this->service->repos->findModule($data["module_id"]);
        if($module && !$module->is_active){
            return response(['errors'=>['error'=>"Votre abonnenement est en cours de traitement, on vous contactera très rapdement pour finaliser le processus"]],422);
        }
        if($this->service->repos->moduelExistForUser($data['user_id'],$data['module_id'])){
            return response(['errors'=>['error'=>'Vous êtes déja souscrit à ce module']],422);
        }
        if(!$module){
            return response(['errors'=>['error'=>'Ressource existe pas']],404);
        }
        $module=$this->service->repos->findModule($data["module_id"]);
        $data["module_id"]=$module->id;
        $data["somme"]=$module->promo_price?$module->promo_price:$module->price;
        $data["title"]=$module->title;
        // NOTIFICATION
        $item=$this->service->create($data);
        $this->service->createNotication($item);
        return response($item,201);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description'=>'nullable|string',
            'module_id'=>'required|numeric',
            'user_id'=>'required|numeric',
        ]);
        if(!$this->service->repos->moduelExistForUser($data['user_id'],$data['module_id'])){
            return response(['errors'=>["error"=>"Utilisateur et module non trouvés"]],422);
        }

        $module=$this->service->repos->findModule($data["module_id"]);
        if(!$module){
            return response(['errors'=>['error'=>'Ressource existe pas']],404);
        }
        $item= $this->service->update($id,$data);
        if($request->hasFile('image')){
            $file_name=ManagerFile::genererChaineAleatoire(2).Carbon::now()->format('Y-m-d').'-'.str_replace(['ô','Ô','é'],['o','o','e','É'],strtolower($item->type));
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.users').'/'.$item->user_id.'/attestations',$file_name);
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
        return response($this->service->delete($id),204);
    }
}
