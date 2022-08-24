<?php

namespace App\Http\Controllers;

use App\Libs\ManagerFile;
use App\Services\PubliciteService;
use Illuminate\Http\Request;

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
            'name'=>'required|string|max:250',
            'title'=>'required|string|max:250|unique:publicites',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $item= $this->service->create($data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.publicites')."/".$item->id."/image",$file_name);
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
            'name'=>'required|string|max:250',
            'title'=>'required|string|max:250',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'=>"nullable|boolean"
        ]);
        if($this->service->repos->exists('title',$data['title'],'id',$id)){
            return  response(["errors"=>["title"=>"le titre existe déja"]],422);
        }
        $item= $this->service->update($id,$data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.publicites')."/".$item->id."/image",$file_name);
            ManagerFile::delete($item->name_file,config('ressources-file.publicites')."/".$item->id."/image");
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
