<?php

namespace App\Http\Controllers;

use App\Services\TopicService;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function __construct(private TopicService $service)
    {}
    public function index(){
        return $this->service->repos->all();
    }
    public function create(Request $request){
        $data=$request->validate([
            'title'=>'required|string|max:255',
            'description'=>'required|string',
            'module_id'=>'required|numeric',
            'is_active'=>'required|boolean',
        ]);
        $data['user_id']=auth()->user()->id;
        return $this->service->create($data);
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
            'title'=>'required|string|max:255',
            'description'=>'required|string',
            'module_id'=>'required|numeric',
            'is_active'=>'required|boolean',
        ]);
        $data['user_id']=auth()->user()->id;
        return $this->service->update($id,$data);
    }
    public function destroy($id){
        return response($this->service->delete($id),204);
    }
}
