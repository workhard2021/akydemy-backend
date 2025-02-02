<?php

namespace App\Http\Controllers;

use App\Libs\ManagerFile;

class ManagerFileController extends Controller
{
    public function __construct(){}
    public function download($url_file){
        if($url_file){
           $url_file= str_replace('api/'.config('app.version'),'',request()->path());
           return ManagerFile::getFile($url_file);
        }
        return '';
    }
    public function generateZip(){
        $files=request()->all();
        return ManagerFile::generateZip($files);
    }
}
