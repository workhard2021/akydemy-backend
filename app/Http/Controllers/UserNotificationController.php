<?php

namespace App\Http\Controllers;
use App\Services\UserNotificationService;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function __construct(private UserNotificationService $service)
    {}
    public function currentUserNotif(){
        return $this->service->repos->currentUserNotif();
    } 
    public function index(){
        return $this->service->repos->all();
    }
    // public function create(Request $request){
    //     $data=$request->validate([
    //          'title'=>'required|string|max:255',
    //          'description'=>'required|string',
    //          'user_id'=>'required|number',
    //     ]);
    //     return $this->service->create($data);
    // }
    // public function show($id){
    //     return $this->service->repos->find($id,['*']);
    // }
    public function update(Request $request,$id){
         $data=$request->validate([
             'event_id'=>'required|numeric'
         ]);
         return response($this->service->update($id,$data));
    }
    public function destroy($id){
        return $this->service->delete($id);
    }
    public function currentUserNoteNotRead(){
         return $this->service->repos->currentUserNoteNotRead();
    }
}
