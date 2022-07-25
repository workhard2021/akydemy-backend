<?php

namespace App\Http\Controllers;

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
            'sub_title'=>'nullable|string|max:250',
            'description'=>'nullable|string',
            'file_url'=>'required|string|max:100',
            'is_active'=>'nullable|boolean',
            'module_id'=>'nullable|numeric',
        ]);
        if($this->service->repos->findOneByColumn('module_id',$data['module_id'])){
            return  response("Le module a déja un programme",403);
        }
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
            'title'=>'required|string',
            'sub_title'=>'nullable|string|max:250',
            'description'=>'nullable|string',
            'is_active'=>'nullable|boolean',
            'file_url'=>'required|string|max:100',
            'module_id'=>'nullable|numeric',
         ]);
        return $this->service->update($id,$data);
    }
    public function destroy($id){
        return $this->service->delete($id);
    }
}
