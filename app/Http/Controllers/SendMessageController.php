<?php

namespace App\Http\Controllers;
use App\Libs\SendMessage;
use Illuminate\Http\Request;

class SendMessageController extends Controller
{

    public function __construct(public SendMessage $sendMessage){}
    
    public function sendMessageWhatsApp(Request $request,){
        $data=$request->validate([
            'description'=>'required|max:500',
            'number'=>'required|max:500',
        ]);
        return response($this->sendMessage->send($data),201); 
    }   
}
