<?php

namespace App\Http\Controllers;

use App\Services\CountryService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct(private CountryService $service)
    {}
    public function index(){
        return $this->service->repos->all();
    }
    public function indexPublic(){
       return $this->service->repos->allPublic();
    }
    public function create(Request $request){
        $data=$request->validate([
            'name'=>'required|string|unique:countries|max:15',
            'title'=>'nullable|string|max:250',
        ]);
        return response($this->service->create($data),201);
    }
    public function show($id){
        $item=$this->service->repos->find($id,['*']);
        if(!$item){
          return  response('NOT FOUND',404);
        }
        return response($item,200);
    }
    public function update(Request $request,$id){
         $data=$request->validate([
               'name'=>'required|string',
               'title'=>'nullable|string|max:250',
         ]);
         if($this->service->repos->exists('name',$data['name'],'id',$id)){
            return  response("Ce pays existe déja",403);
         }
        return response($this->service->update($id,$data),200);
    }
    public function destroy($id){
         return response($this->service->delete($id),204);
    }
}
