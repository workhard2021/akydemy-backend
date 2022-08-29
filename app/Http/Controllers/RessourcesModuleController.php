<?php
namespace App\Http\Controllers;

use App\Enums\eTypeFile;
use App\Libs\ManagerFile;
use App\Services\RessourceModuleService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RessourcesModuleController extends Controller
{
    public function __construct(private RessourceModuleService $service)
    {}
    public function  index($search=''){
        return $this->service->repos->searchResourceModule(null,$search,$published=1);
    }
    public function ressourceFormModule($id='',$search=''){
        // resource with module id and text searchable et text or id are not required
        return $this->service->repos->searchResourceModule($id,$search);
    }
    public function  searchResourceModuleAdmin($search=''){
        return $this->service->repos->searchResourceModuleAdmin($search);
    }
    public function  searchResourceModuleStudiant($search=''){
        return $this->service->repos->searchResourceModuleStudiant($search);
    }
    public function create(Request $request){
        $data=$request->validate([
            'title'=>'required|string|max:255',
           
            'module_id'=>'required|numeric',
            'is_default'=>'boolean',
            'is_public'=>'boolean',
            'description'=>'nullable|string'
        ]);
        // return $request->video->getClientOriginalName();
        return $data;
        $item=$this->service->create($data);
        if($request->hasFile('video')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['video'],config('ressources-file.ressources-modules').'/'.$item->module_id.'/videos',$file_name);
            ManagerFile::delete($item->name_movie,config('ressources-file.ressources-modules').'/'.$item->module_id.'/videos');
            $item->url_movie=$file_name['url'];
            $item->name_movie=$file_name['name'];
            $item->save();
        }
        if($request->hasFile('fichier')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['fichier'],config('ressources-file.ressources-modules').'/'.$item->module_id.'/pdf',$file_name);
            ManagerFile::delete($item->name_pdf,config('ressources-file.ressources-modules').'/'.$item->module_id.'/pdf');
            $item->url_pdf=$file_name['url'];
            $item->name_pdf=$file_name['name'];
            $item->save();
        }
        return response($item,201);
    }
    public function show($id){
        return $this->service->repos->find($id,['*']);
    }
    public function update(Request $request,$id){
        $data=$request->validate([
            'title'=>'required|string|max:255',
            'video' => 'nullable|file|mimetypes:video/mp4',
            'fichier'=>'nullable|mimes:'.implode(",",eTypeFile::getValues()),
            'module_id'=>'required|numeric',
            'is_default'=>'nullable|boolean',
            'is_public'=>'nullable|boolean',
            'description'=>'nullable|string'
        ]);
        $item=$this->service->update($id,$data);
        if($request->hasFile('video')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['video'],config('ressources-file.ressources-modules').'/'.$item->module_id.'/videos',$file_name);
            ManagerFile::delete($item->name_movie,config('ressources-file.ressources-modules').'/'.$item->module_id.'/videos');
            $item->url_movie=$file_name['url'];
            $item->name_movie=$file_name['name'];
            $item->save();
        }
        if($request->hasFile('fichier')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['fichier'],config('ressources-file.ressources-modules').'/'.$item->module_id.'/pdf',$file_name);
            ManagerFile::delete($item->name_pdf,config('ressources-file.ressources-modules').'/'.$item->module_id.'/pdf');
            $item->url_pdf=$file_name['url'];
            $item->name_pdf=$file_name['name'];
            $item->save();
        }
        return response($item,200);
    }
    public function destroy($id){
        return $this->service->delete($id);
    }
}
