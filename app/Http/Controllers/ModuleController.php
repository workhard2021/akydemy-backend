<?php

namespace App\Http\Controllers;

use App\Services\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function __construct(private ModuleService $service)
    {}
   
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }
    public function indexPublic(){
        return $this->service->repos->allPublic();
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
        ]);
        return response($this->service->create($data),201);
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
            'title' => 'required|string|max:50',
            'sub_title' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'promo_price' => 'nullable|numeric',
            'price' => 'required|numeric',
            'default_movie_url' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'owner_id' => 'required|numeric',
            'categorie_id' => 'required|numeric',
         ]);
         if($this->service->repos->exists('title',$data['title'],'id',$id)){
            return  response("Ce pays existe déja",403);
         }
         return $this->service->update($id,$data);
    }
    public function destroy($id){
        return $this->service->delete($id);
    }
}
