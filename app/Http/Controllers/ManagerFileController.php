<?php

namespace App\Http\Controllers;

use App\Libs\ManagerFile;
use App\Services\CategorieService;
use Illuminate\Http\Request;

class ManagerFileController extends Controller
{
    public function __construct(){}
    public function download($url_file){
        if($url_file){
           $url_file= str_replace('api/v0.1/','',request()->path());
           return ManagerFile::getFile($url_file);
        }
        return '';
    }
}
