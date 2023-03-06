<?php
namespace App\Http\Controllers;

use App\Enums\eTypeFile;
use App\Events\EventSendFile;
use App\Libs\ManagerFile;
use App\Services\RessourceModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RessourcesModuleController extends Controller
{
    public function __construct(private RessourceModuleService $service)
    {}
    // public function  index($search=''){
    //     return $this->service->repos->searchResourceModule(null,$search,$published=1);
    // }
    public function ressourceFormModule($id='',$search=''){
        // resource with module id and text searchable et text or id are not required
        return $this->service->repos->searchResourceModule($id,$search);
    }
    public function  searchResourceModuleAdmin($search=''){
        return $this->service->repos->searchResourceModuleAdmin($search);
    }
    public function  searchResourceModuleTeacher($search=''){
        return $this->service->repos->searchResourceModuleTeacher($search);
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
        $item=$this->service->create($data);
        if($request->hasFile('video')){
            $module=$item->module;
            $path=config('ressources-file.modules')."/$module->categorie_id/module/$module->id/video";
            $file_name='';
            $file_name=ManagerFile::upload(request()->file('video'),$path,$file_name);
            ManagerFile::deleteWithUrl($item->url_movie_remove);
            ManagerFile::deleteWithUrl($item->url_pdf);
            $item->url_pdf=null;
            $item->name_pdf=null;
            if(config('ressources-file.disk')=="s3"){
                $item->url_movie=Storage::disk('s3')->url($file_name['url']);
            }else{
                $item->url_movie=$file_name['url'];
            }
            $item->url_movie_remove=$file_name['url'];
            $item->name_movie=$file_name['name'];
            $item->save();
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
            $file_name='';
            $file_name=ManagerFile::upload(request()->file('video'),$path,$file_name);
            ManagerFile::deleteWithUrl($item->url_movie_remove);
            ManagerFile::deleteWithUrl($item->url_pdf);
            $item->url_pdf=null;
            $item->name_pdf=null;
            if(config('ressources-file.disk')=="s3"){
                $item->url_movie=Storage::disk('s3')->url($file_name['url']);
            }else{
                $item->url_movie=$file_name['url'];
            }
            $item->url_movie_remove=$file_name['url'];
            $item->name_movie=$file_name['name'];
            $item->save();
        }

        if($request->hasFile('fichier')){
            $module=$item->module;
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['fichier'],config('ressources-file.modules')."/$module->categorie_id/module/$module->id/pdf",$file_name);
            ManagerFile::deleteWithUrl($item->url_pdf);
            ManagerFile::deleteWithUrl($item->url_movie_remove);
            $item->url_pdf=$file_name['url'];
            $item->name_pdf=$file_name['name'];
            $item->url_movie=null;
            $item->url_movie_remove=null;
            $item->name_movie=null;
            $item->save();
        }
        return response($item,200);
    }

    public function destroy($id){
        $item=$this->service->repos->find($id);
        if($item && $item->url_pdf){
            ManagerFile::deleteWithUrl($item->url_pdf);
        }
        if($item && $item->url_movie_remove){
            ManagerFile::deleteWithUrl($item->url_movie_remove);
        }
        return $this->service->delete($id);
    }
}
