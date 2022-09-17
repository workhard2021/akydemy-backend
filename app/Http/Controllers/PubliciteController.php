<?php

namespace App\Http\Controllers;

use App\Enums\eTypeImage;
use App\Libs\ManagerFile;
use App\Services\PubliciteService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PubliciteController extends Controller
{
    public function __construct(private PubliciteService $service)
    {}
    public function lists(){
        return $this->service->repos->listNotPaginate();
    }
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }
    public function create(Request $request){
        $data=$request->validate([
            'name'=>'required|string|max:50',
            'title'=>'required|string|max:50|unique:publicites',
            'url'=>'required|max:150',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
        ]);
        $item= $this->service->create($data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.publicites')."/images",$file_name);
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
    public function update(Request $request,$id){
        $data=$request->validate([
            'name'=>'required|string|max:50',
            'title'=>'required|string|max:50',
            'url'=>'required|max:150',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
            'is_active'=>"boolean"
        ]);
        if($this->service->repos->exists('title',$data['title'],'id',$id)){
            return  response(["errors"=>["title"=>"le titre existe déja"]],422);
        }
        $item= $this->service->update($id,$data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.publicites')."/images",$file_name);
            ManagerFile::deleteWithUrl($item->url_file);
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
            $item->save();
        }
        return response($item,200);
    }
    public function destroy($id){
        $item=$this->service->repos->find($id);
        if($item && $item->url_file){
            ManagerFile::deleteWithUrl($item->url_file);
        }
        return response($this->service->delete($id),204);
    }
}
