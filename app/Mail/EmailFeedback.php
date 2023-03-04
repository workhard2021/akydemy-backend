<?php

namespace App\Mail;

use App\Libs\ManagerFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EmailFeedback extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $data,public $response=null)
    {}
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {    
        if(isset($this->data['fichier'])){
            $name=$this->data['fichier']->getClientOriginalName();
            $folder=DIRECTORY_SEPARATOR.'export-file'.DIRECTORY_SEPARATOR;
            $path=$folder.$name;
            $this->data['fichier']->storeAs($folder,$name,'export');
            return $this->view('email.feedback')
            ->replyTo($this->data['email'],config('app.name'))
            ->subject($this->data['title'])
            ->attach(public_path($path));
        }
        return $this->view('email.feedback')
          ->replyTo($this->data['email'],config('app.name'))
          ->subject($this->data['title']);
    }
}
