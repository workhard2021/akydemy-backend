<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(private QuizService $service)
    {}
    // clear after
    public function index(){
        return response($this->service->repos->all(),200);
    }
    public function quizzesStudiant(){
        return response($this->service->repos->quizzesStudiant(),200);
    }
    public function quizzesModule($id){
        return response($this->service->repos->quizzesModule($id),200);
    }
    public function create(Request $request){
        $data=$request->validate([
            'quiz'=>'required|string|max:250|unique:quizzes',
            'answer'=>'required|string|max:250',
            'fake_answer'=>'required|array',
            'is_active'=>'required|boolean',
            'module_id'=>'required|numeric'
        ]);
        $data['fake_answer']=json_encode($data['fake_answer'],true);
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
            'quiz'=>'required|string|max:250',
            'answer'=>'required|string|max:250',
            'fake_answer'=>'required|array',
            'is_active'=>'required|boolean',
            'module_id'=>'required|numeric'
        ]);
         $data=$request->validate([
            'quiz'=>'required|string|max:250',
            'answer'=>'required|string|max:250',
            'fake_answer'=>'required|array',
            'module_id'=>'required|numeric'
        ]);
        $errors=null;
        if($this->service->repos->exists('quiz',$data['quiz'],'id',$id)){
            $errors['quiz']="Cette question existe déja";
        }
        if($errors){
            return $errors;
        }
        $data['fake_answer']=json_encode($data['fake_answer'],true);
        return response($this->service->update($id,$data),200);
    }
    public function deleteQuizModule($id){
        return response($this->service->deleteQuizModule($id),204);
    }
    public function destroy($id){
        return response($this->service->delete($id),204);
    }
}
