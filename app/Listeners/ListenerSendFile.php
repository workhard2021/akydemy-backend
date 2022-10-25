<?php

namespace App\Listeners;

use App\Events\EventSendFile;
use App\Libs\ManagerFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenerSendFile
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
     * @param  \App\Events\EventSendFile  $event
     * @return void
     */
    public function handle(EventSendFile $event)
    {
         $item=$event->instance;
         $path=$event->path;
         $is_updated=$event->is_updated;
        if(request()->hasFile('video')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload(request()->file('video'),$path,$file_name);
            if($is_updated){
                ManagerFile::deleteWithUrl($item->url_movie);
                ManagerFile::deleteWithUrl($item->url_pdf);
                $item->url_pdf="";
                $item->name_pdf="";
            }
            $item->url_movie=ManagerFile::getFile($file_name['url'],true);
            $item->name_movie=$file_name['name'];
            $item->save();
        }
    }
}
