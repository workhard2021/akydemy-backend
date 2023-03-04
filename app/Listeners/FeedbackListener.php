<?php

namespace App\Listeners;

use App\Events\FeedbackEvent;
use App\Mail\EmailFeedback;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class FeedbackListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\FeedbackEvent  $event
     * @return void
     */
    public function handle(FeedbackEvent $event)
    {  
        if(isset($event->data['emails']) && is_array($event->data['emails'])){
            $event->data['email']=config('mail.mailers.smtp.username');
            foreach ($event->data['emails'] as $email){
                 Mail::to($email)->send(new EmailFeedback($event->data,true));
            }
        }else{
           if(isset($event->data['email']) && $event->data['email']){
               Mail::to(config('mail.mailers.smtp.username'))->send(new EmailFeedback($event->data));
           }
        }
    }
}
