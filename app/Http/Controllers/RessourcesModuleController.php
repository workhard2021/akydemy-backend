<?php
namespace App\Http\Controllers;

use App\Enums\eTypeFile;
use App\Events\EventSendFile;
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
            'video' => 'nullable|file|mimetypes:video/mp4',
            'fichier'=>'nullable|mimes:'.implode(",",eTypeFile::getValues()),
            'module_id'=>'required|numeric',
            'is_default'=>'boolean',
            'is_public'=>'boolean',
            'description'=>'nullable|string'
        ]);
        // return $request->video->getClientOriginalName();
        $item=$this->service->create($data);
        if($request->hasFile('video')){
            $module=$item->module;
            $path=config('ressources-file.modules')."/$module->categorie_id/module/$module->id/video";
            EventSendFile::dispatch($item,$path);
        }

        if($request->hasFile('fichier')){
            $module=$item->module;
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['fichier'],config('ressources-file.modules')."/$module->categorie_id/module/$module->id/pdf",$file_name);
            ManagerFile::deleteWithUrl($item->url_pdf);
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
            $module=$item->module;
            $path=config('ressources-file.modules')."/$module->categorie_id/module/$module->id/video";
            EventSendFile::dispatch($item,$path,true);
        }
        if($request->hasFile('fichier')){
            $module=$item->module;
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['fichier'],config('ressources-file.modules')."/$module->categorie_id/module/$module->id/pdf",$file_name);
            ManagerFile::deleteWithUrl($item->url_pdf);
            ManagerFile::deleteWithUrl($item->url_movie);
            $item->url_pdf=$file_name['url'];
            $item->name_pdf=$file_name['name'];
            $item->url_movie="";
            $item->name_movie="";
            $item->save();
        }
        return response($item,200);
    }

    public function destroy($id){
        $item=$this->service->repos->find($id);
        if($item && $item->url_pdf){
            ManagerFile::deleteWithUrl($item->url_pdf);
        }
        if($item && $item->url_movie){
            ManagerFile::deleteWithUrl($item->url_movie);
        }
        return $this->service->delete($id);
    }
}
