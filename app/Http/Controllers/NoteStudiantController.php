<?php

namespace App\Http\Controllers;

use App\Services\NoteStudiantService;
use Illuminate\Http\Request;

class NoteStudiantController extends Controller
{
    public function __construct(private NoteStudiantService $service)
    {}
    public function index(){
        return response($this->service->repos->all(),200);
    }
    public function create(Request $request){
        $data=$request->validate([
            'note'=>'required|numeric',
            'user_id'=>'required|numeric',
            'module_id'=>'required|numeric'
        ]);
        if($this->service->repos->getEvaluation($data['module_id'],$data['user_id'])){
           return response(['message'=>'Vous avez déja fait le test pour ce module'],403);
        }
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
            'note_teache'=>'required|numeric',
            'user_id'=>'required|numeric',
            'module_id'=>'required|numeric'
        ]);
        $data['teacher_id']=auth()->user()->id;
        return response($this->service->updateNote($id,$data),200);
    }
    public function destroy($id){
        return response($this->service->delete($id),204);
    }
}
