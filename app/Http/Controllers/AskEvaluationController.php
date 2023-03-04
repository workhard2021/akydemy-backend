<?php

namespace App\Http\Controllers;

use App\Services\AskEvaluationService;
use Illuminate\Http\Request;

class AskEvaluationController extends Controller
{
    public function __construct(public AskEvaluationService $service)
    {}
    public function index($text='',$moduleId='',$dateBegin='',$dateEnd=''){
        return response($this->service->repos->search($text,$moduleId,$dateBegin,$dateEnd),200); 
    }
    public function askEvaluation(Request $request){
        $data=$request->validate([
            'module_id'=>'required|numeric',
            'ask'=>'required|boolean'
        ]);
        return response($this->service->create($data),201); 
    }

    public function acceptedEvaluation(Request $request,$id){
        $data=$request->validate([
            'accepted'=>'required|boolean'
        ]);
        return response($this->service->update($id,$data),201); 
    }
    public function destroy($id){
        return response($this->service->delete($id),204); 
    }
}
