<?php
namespace App\Libs;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendMessage {

    public function send($data){
     $url = "https://messages-sandbox.nexmo.com/v1/messages";
     $params = [
        "from"=> "14157386102",
        "to"=> $data['number'],
        "message_type"=> "text",
        "text"=>"This is a WhatsApp Message sent from the Messages API",
        "channel"=>"whatsapp"
      ];
      $secret ='Basic'.base64_encode(env('NEXMO_API_KEY'). ":" . env('NEXMO_API_SECRET'));
      $response = Http::withToken($secret)->withHeaders(["Content-Type"=>"application/json","Accept"=>"application/json"])->post($url,$params);
      $data = $response->body();
      Log::Info($data);
      return $data;
    }
}