<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function __construct(private MessageService $service)
    {}
    public function index(){
        return response($this->service->repos->all(),200);
    }
    public function messagesTopic($id){
       return response($this->service->repos->messagesTopic($id),200);
    }
    public function create(Request $request){
        $data=$request->validate([
            'description'=>'required',
            'topic_id'=>'required|numeric',
            'is_active'=>'required|boolean'
        ]);
        $data['user_id']=auth()->user()->id;
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
            'description'=>'required',
            'topic_id'=>'required|numeric',
            'is_active'=>'required|boolean'
        ]);
        $data['user_id']=auth()->user()->id;
        return response($this->service->update($id,$data),200);
    }
    public function destroy($id){
        return response($this->service->delete($id),204);
    }
}
