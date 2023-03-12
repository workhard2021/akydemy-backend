<?php

namespace App\Http\Controllers;

use App\Enums\eTypeFile;
use App\Enums\eTypeImage;
use App\Events\FeedbackEvent;
use App\Services\AvisService;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    public function __construct(public AvisService $service)
    {}
    public function index($text=''){
        return response($this->service->repos->searchText($text),200); 
    }
    public function show($id){
        return response($this->service->repos->find($id),200); 
    }
    public function store(Request $request){
        $data=$request->validate([
            'description'=>'required|max:500',
            'title'=>'required|max:40'
        ]);
        $data['read']=false;
        $data['email']="Avis message";
        return response($this->service->create($data),201); 
    }
    public function update($id,Request $request){
         $data=$request->validate([
            'read'=>'required|boolean'
        ]);
        return response($this->service->update($id,$data,false),200); 
    }
    public function destroy($id){
        return response($this->service->delete($id),204); 
    }
    public function feedback(Request $request){
        $data=$request->validate([
            'description'=>'required|string',
            'title'=>'required|string',
            'emails'=>'required',
            'fichier' => 'nullable|mimes:'.implode(',',[...eTypeFile::getValues(),...eTypeImage::getValues()]),
        ]);
        $data['emails']=explode(',',$data['emails']);
        FeedbackEvent::dispatch($data);
        return response('email envoyé',200);
    }
    public function ContactUs(Request $request){
        $data=$request->validate([
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'description'=>'required|string',
            'title'=>'required|string',
            'email'=>'required|email'
        ]);
        $data['read']=false;
        $this->service->create($data);
        return response('email envoyé',200);
    }
    
}
