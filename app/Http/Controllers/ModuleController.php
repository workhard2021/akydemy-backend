<?php

namespace App\Http\Controllers;

use App\Enums\eTypeImage;
use App\Libs\ManagerFile;
use App\Services\ModuleService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    public function __construct(private ModuleService $service)
    {}
    
    public function moduleforExams($search=''){
        return $this->service->repos->moduleforExams($search);
    }
    
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }
    public function indexPublic(){
        return $this->service->repos->allPublic();
    }

    public function listNotPaginate(){
        return $this->service->repos->listNotPaginate();
    }
    public function create(Request $request){
        $data=$request->validate([
            'title' => 'required|string|max:50|unique:modules',
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
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.ressources-modules')."/".$item->id."/image",$file_name);
            ManagerFile::delete($item->name_image,config('ressources-file.ressources-modules')."/".$item->id."/image");
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
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
            'title' => 'required|string|max:50',
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
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.ressources-modules')."/".$item->id."/image",$file_name);
            ManagerFile::delete($item->name_file,config('ressources-file.ressources-modules')."/".$item->id."/image");
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
            $item->save();
        }
        return response($item,200);
    }

    public function destroy($id){
        return $this->service->delete($id);
    }
}
