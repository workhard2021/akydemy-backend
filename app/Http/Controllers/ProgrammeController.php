<?php

namespace App\Http\Controllers;

use App\Libs\ManagerFile;
use App\Services\ProgrammeService;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function __construct(private ProgrammeService $service)
    {}
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }
    public function indexPublic(){
        return $this->service->repos->allPublic();
    }
    public function create(Request $request){
        $data=$request->validate([
            'title'=>'required|string',
            'sub_title'=>'required|string|max:250',
            'is_active'=>'nullable|boolean',
            'module_id'=>'required|numeric|unique:programmes',
            'description'=>'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $item=$this->service->create($data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.ressources-programmes'),$file_name);
            ManagerFile::delete($item->name_file,config('ressources-file.ressources-programmes'));
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if($this->service->repos->exists('id',$id,'module_id',$data['module_id'])){
            return response(["errors"=>["module_id"=>"Le module a déja un programme"]],422);
        }
        $item=$this->service->update($id,$data);
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.ressources-programmes'),$file_name);
            ManagerFile::delete($item->name_file,config('ressources-file.ressources-programmes'));
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
            $item->save();
        }
        return response($item,201);
    }
    public function destroy($id){
        return $this->service->delete($id);
    }
}
