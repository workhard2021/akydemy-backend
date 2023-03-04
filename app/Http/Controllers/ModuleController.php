<?php

namespace App\Http\Controllers;

use App\Enums\eTypeImage;
use App\Libs\ManagerFile;
use App\Services\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function __construct(private ModuleService $service)
    {}
    
    public function moduleforExams($search=''){
        return response($this->service->repos->moduleforExams($search),200);
    }

    public function index($search=''){
        return response($this->service->repos->searchText($search),200);
    }

    public function indexPublic($search='default'){
        return response($this->service->repos->allPublic($search),200);
    }

    public function listNotPaginate(){
        return response($this->service->repos->listNotPaginate(),200);
    }
    public function listNotPaginatePublic(){
        return response($this->service->repos->listNotPaginatePublic(),200);
    }
    public function create(Request $request){
        $data=$request->validate([
            'title' => 'required|string|max:250|unique:modules',
            'sub_title' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'promo_price' => 'nullable|numeric',
            'price' => 'required|numeric',
            'default_movie_url' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'owner_id' => 'required|numeric',
            'categorie_id' => 'required|numeric',
            'nbr_month' => 'nullable|numeric',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
        ]);
        $item=$this->service->create($data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $folderName=config('ressources-file.modules')."/$item->categorie_id/module/$item->id";
            $file_name=ManagerFile::upload($data['image'],$folderName,$file_name);
            ManagerFile::deleteWithUrl($item->url_file);
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
            $item->folder_name=$folderName;
            $item->save();
        }
        return response($item,201);
    }
    public function show($id){
        $item=$this->service->repos->find($id,['*']);
        if(!$item){
             return response('NOT FOUND',404);
        }
        return response($item,200);
    }
    public function showModuleRessource($id){
        $item=$this->service->repos->showModuleRessource($id);
        if(!$item){
             return response('NOT FOUND',404);
        }
        return response($item,200);
    }
    public function update(Request $request,$id){
         $data=$request->validate([
            'title' => 'required|string|max:250',
            'sub_title' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'promo_price' => 'nullable|numeric',
            'price' => 'required|numeric',
            'default_movie_url' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'owner_id' => 'required|numeric',
            'categorie_id' => 'required|numeric',
            'nbr_month' => 'nullable|numeric',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
         ]);
         if($this->service->repos->exists('title',$data['title'],'id',$id)){
            return  response(["errors"=>["title"=>"le titre existe déja"]],422);
         }
         $item=$this->service->update($id,$data);
         if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $folderName=config('ressources-file.modules')."/$item->categorie_id/module/$item->id";
            $file_name=ManagerFile::upload($data['image'],$folderName,$file_name);
            ManagerFile::deleteWithUrl($item->url_file);
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
            $item->folder_name=$folderName;
            $item->save();
        }
        return response($item,200);
    }
    public function destroy($id){
        $item=$this->service->repos->find($id);
        if($item && $item->folder_name){
            ManagerFile::deleteDirectory($item->folder_name);
        }
        return $this->service->delete($id);
    }
    public function adminModulesVideo(){
        return $this->service->repos->adminModulesVideo();
    }
}
