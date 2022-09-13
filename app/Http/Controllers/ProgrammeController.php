<?php

namespace App\Http\Controllers;

use App\Enums\eTypeFile;
use App\Enums\eTypeImage;
use App\Libs\ManagerFile;
use App\Services\ProgrammeService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgrammeController extends Controller
{
    public function __construct(private ProgrammeService $service)
    {}
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }
    public function indexPublic($search=''){
        return $this->service->repos->allPublic($search);
    }
    public function create(Request $request){
        $data=$request->validate([
            'title'=>'required|string',
            'sub_title'=>'required|string|max:250',
            'is_active'=>'nullable|boolean',
            'module_id'=>'required|numeric|unique:programmes',
            'description'=>'nullable|string',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
            'file_dowload'=>'nullable|mimes:'.implode(",",eTypeFile::getValues())
        ]);
        $item=$this->service->create($data);
        if($request->hasFile('image')){
            $module=$item->module;
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.modules')."/$module->categorie_id/module/$module->id/programme",$file_name);
            ManagerFile::deleteWithUrl($item->url_file);
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
            $item->save();
        }
        if($request->hasFile('file_dowload')){
            $module=$item->module;
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['file_dowload'],config('ressources-file.modules')."/$module->categorie_id/module/$module->id/programme",$file_name);
            ManagerFile::deleteWithUrl($item->url_file_dowload);
            $item->url_file_dowload=$file_name['url'];
            $item->name_file_dowload=$file_name['name'];
            $item->save();
        }
        return response($item,201);
    }
    public function show($id){
         $item=$this->service->repos->findProgrammeWithProf($id);
         if(!$item){
           return  response('NOT FOUND',404);
         }
        return response($item,200);
    }
    public function update(Request $request,$id){
        $data=$request->validate([
            'title'=>'required|string',
            'sub_title'=>'required|string|max:250',
            'is_active'=>'nullable|boolean',
            'module_id'=>'required',
            'description'=>'nullable|string',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
            'file_dowload'=>'nullable|mimes:'.implode(",",eTypeFile::getValues())
        ]);
        
        if($this->service->repos->exists('id',$id,'module_id',$data['module_id'])){
            return response(["errors"=>["module_id"=>"Le module a déja un programme"]],422);
        }
        $item=$this->service->update($id,$data);
        if($request->hasFile('image')){
            $module=$item->module;
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.modules')."/$module->categorie_id/module/$module->id/programme",$file_name);
            ManagerFile::deleteWithUrl($item->url_file);
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
            $item->save();
        }
        if($request->hasFile('file_dowload')){
            $module=$item->module;
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['file_dowload'],config('ressources-file.modules')."/$module->categorie_id/module/$module->id/programme",$file_name);
            ManagerFile::deleteWithUrl($item->url_file_dowload);
            $item->url_file_dowload=$file_name['url'];
            $item->name_file_dowload=$file_name['name'];
            $item->save();
        }
        return response($item,201);
    }
    public function destroy($id){
        $item=$this->service->repos->find($id);
        if($item && $item->url_file){
            ManagerFile::deleteWithUrl($item->url_file);
        }
        if($item && $item->url_file_dowload){
            ManagerFile::deleteWithUrl($item->url_file_dowload);
        }
        return $this->service->delete($id);
    }
}
