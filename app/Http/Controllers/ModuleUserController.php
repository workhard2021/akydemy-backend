<?php

namespace App\Http\Controllers;

use App\Enums\eStatusAttestation;
use App\Enums\eTypeCertificate;
use App\Services\ModuleUserService;
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
            'title'=>'required|string',
            'somme'=>'nullable|numeric',
            'module_id'=>'required|numeric',
            'user_id'=>'nullable|numeric',
        ]);

        if($this->service->repos->moduelExistForUser($data['user_id'],$data['module_id'])){
             return response(['error'=>'Vous êtes déja souscrit à ce module'],403);
        }
        return response($this->service->create($data),201);
    }
    public function show($id){
        return response($this->service->repos->find($id,['*']),200);
    }
    public function update(Request $request,$id){
         $data=$request->validate([
            'title'=>'required|string',
            'somme'=>'nullable|numeric',
            'type'=>'required|string|'.Rule::in(eTypeCertificate::getValues()),
            'status_attestation'=>'required|string|'.Rule::in(eStatusAttestation::getValues()),
            'is_valide'=>'boolean',
            'file_attestaion_url'=>'nullable|string',
            'description'=>'nullable|string',
            'module_id'=>'required|numeric',
            'user_id'=>'required|numeric',
         ]);
         if(!$this->service->repos->moduelExistForUser($data['user_id'],$data['module_id'])){
            return response(['error'=>'Utilisateur et module non trouvés'],404);
         }
         return response($this->service->update($id,$data),200);
    }
    public function destroy($id){
        return response($this->service->delete($id),204);
    }
}
