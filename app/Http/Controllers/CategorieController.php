<?php

namespace App\Http\Controllers;

use App\Enums\eTypeImage;
use App\Libs\ManagerFile;
use App\Services\CategorieService;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function __construct(private CategorieService $service)
    {}
    public function listNotPaginate(){
        return $this->service->repos->listNotPaginate();
    }
    public function listNotPaginatePublic(){
        return $this->service->repos->listNotPaginatePublic();
    }
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }
    public function create(Request $request){
        $data=$request->validate([
            'name'=>'required|string|unique:categories',
            'title'=>'nullable|string|max:450',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
        ]);
        $item= $this->service->create($data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $folderName=config('ressources-file.categories')."/".$item->id;
            $file_name=ManagerFile::upload($data['image'],$folderName,$file_name);
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
    public function update(Request $request,$id){
        $data=$request->validate([
            'name'=>'required|string',
            'title'=>'nullable|string|max:450',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
        ]);
        if($this->service->repos->exists('name',$data['name'],'id',$id)){
            return  response(["errors"=>["name"=>"le nom existe déja"]],422);
        }
        $item= $this->service->update($id,$data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $folderName=config('ressources-file.categories')."/".$item->id;
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
        return response($this->service->delete($id),204);
    }
}
