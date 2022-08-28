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
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }
    public function create(Request $request){
        $data=$request->validate([
            'name'=>'required|string|unique:categories',
            'title'=>'nullable|string|max:250',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
        ]);
        $item= $this->service->create($data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.categories'),$file_name);
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
            'name'=>'required|string',
            'title'=>'nullable|string|max:250',
            'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
        ]);
        if($this->service->repos->exists('name',$data['name'],'id',$id)){
            return  response(["errors"=>["name"=>"le nom existe déja"]],422);
        }
        $item= $this->service->update($id,$data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.categories'),$file_name);
            ManagerFile::delete($item->name_file,config('ressources-file.categories'));
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
            $item->save();
        }
        return response($item,200);
    }
    public function destroy($id){
        return response($this->service->delete($id),204);
    }
}
