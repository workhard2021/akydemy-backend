<?php

namespace App\Http\Controllers;

use App\Services\RessourceModuleService;
use Illuminate\Http\Request;

class RessourcesModuleController extends Controller
{
    public function __construct(private RessourceModuleService $service)
    {}
    public function  index($search=''){
        return $this->service->repos->searchResourceModule(null,$search,1);
    }
    public function ressourceFormModule($id='',$search=''){
        return $this->service->repos->searchResourceModule($id,$search);
    }
    public function create(Request $request){
        $data=$request->validate([
            'title'=>'required|string|max:255',
            'movie_module_url'=>'nullable|string',
            'pdf_resource'=>'nullable|string|max:250',
            'module_id'=>'required|numeric',
            'default_resource'=>'required|boolean',
            'is_public'=>'required|boolean'
        ]);
        return $this->service->create($data);
    }
    public function show($id){
        return $this->service->repos->find($id,['*']);
    }
    public function update(Request $request,$id){
         $data=$request->validate([
            'title'=>'required|string|max:255',
            'movie_module_url'=>'nullable|string',
            'pdf_resource'=>'nullable|string|max:250',
            'module_id'=>'required|numeric',
            'default_resource'=>'required|boolean',
            'is_public'=>'required|boolean'
         ]);
        return $this->service->update($id,$data);
    }
    public function destroy($id){
        return $this->service->delete($id);
    }
}
