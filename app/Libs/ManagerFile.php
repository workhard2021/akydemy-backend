<?php
namespace App\Libs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ManagerFile {
   public static function upload($arrayFileName,$folder,$file_name=null){
      $folder = $folder . DIRECTORY_SEPARATOR;
      $name= $arrayFileName->getClientOriginalName();
      $name= time().'-'.$name;
      $name= self::removeSpeciauxCaractere($name);
      $arrayFileName->storeAs($folder,$name,config('ressources-file.disk'));
      return ['url'=>$folder.$name,'name'=>$name];
   }

   public static function  delete($file_names,$folder){
      $paths=[];$count=0;
      if(is_array($file_names)){
         foreach ($file_names as $value) {
            $path=$folder.DIRECTORY_SEPARATOR.$value;
            if (Storage::disk(config('ressources-file.disk'))->exists($path)) {
               $paths[]=$path;
            }
            $count++;
         }
         Storage::disk(config('ressources-file.disk'))->delete($paths);
         return $count;
      }
      $path= $folder . DIRECTORY_SEPARATOR . $file_names;
      if(Storage::disk(config('ressources-file.disk'))->exists($path)){
         return Storage::disk(config('ressources-file.disk'))->delete($path);
      }
      return null;
   }
   
   public static function  deleteDirectory($directory){
      $count=0;
      if($directory && is_array($directory)){
         foreach ($directory as $value) {
            $count++;
            if($directory && Storage::disk(config('ressources-file.disk'))->exists($value)){
               Storage::deleteDirectory($value);
            }
         }
         return $count;
      }
   
      if($directory && Storage::disk(config('ressources-file.disk'))->exists($directory)){
         Storage::deleteDirectory($directory);
      }
      return null;
   }

   public static function  deleteWithUrl($file_names){
      $paths=[];$count=0;
     
      if($file_names && is_array($file_names)){
         foreach ($file_names as $value) {
            $path=$value;
            if (Storage::disk(config('ressources-file.disk'))->exists($path)) {
               $paths[]=$path;
            }
            $count++;
         }
         Storage::disk(config('ressources-file.disk'))->delete($paths);
         return $count;
      }
      $path=$file_names;
      if($path && Storage::disk(config('ressources-file.disk'))->exists($path)){
         return Storage::disk(config('ressources-file.disk'))->delete($path);
      }
      return null;
   }
   
   public static function getFile($file_name,$getUrlS3=false){
      if($getUrlS3){
         return Storage::disk(config('ressources-file.disk'))->url($file_name);
      }
      if(Storage::disk(config('ressources-file.disk'))->exists($file_name)){
        return Storage::disk(config('ressources-file.disk'))->download($file_name);
      }
      return '';
   }
   public static function deplacerFile($filenames,$folder){
      $newArray=[];
      $from = config("ressources-file.tmp").DIRECTORY_SEPARATOR;
      $to = $folder.DIRECTORY_SEPARATOR;
      if(!is_array($filenames)){
         $f = $from . $filenames;
         $t = $to . $filenames;
         if(Storage::disk(config('ressources-file.disk'))->exists($f)) {
            $file= $filenames;
            Storage::disk(config('ressources-file.disk'))->move($f,$t);
         }
         return $file;
      }
      if($folder && $filenames){
         foreach($filenames as $value){
            $f = $from . $value;
            $t = $to . $value;
            if(Storage::disk(config('ressources-file.disk'))->exists($f)){
               $newArray[]=$value;
               Storage::disk(config('ressources-file.disk'))->move($f,$t);
            }
            $f = $t = null;
         }
      }
      return $newArray;
   }
   public static function deplacerFileExcept($file,$from,$to)
   {     
         $from = config("ressources-file.$from") . DIRECTORY_SEPARATOR . $file;
         $to = config("ressources-file.$to") . DIRECTORY_SEPARATOR . $file;
         if(Storage::disk(config('ressources-file.disk'))->exists($from)) {
            if(!Storage::disk(config('ressources-file.disk'))->exists($to)){
                 Storage::disk(config('ressources-file.disk'))->copy($from, $to);
            }
            return true;
         }
         return false;
   }

   public static function exist($filenames,$folder)
   {
      $newArray = [];
      $path = config("ressources-file.$folder").DIRECTORY_SEPARATOR;
      if (!is_array($filenames)) {
         $filenames=[$filenames];
      }
      if($folder && $filenames) {
         foreach ($filenames as $value) {
            $f = $path . $value;
            if (Storage::disk(config('ressources-file.disk'))->exists($f)) {
               $newArray[] = $value;
            }
            $f = null;
         }
      }
      return $newArray;
   }
   public static function isExist($filenames, $folder)
   {
      $path = config("ressources-file.$folder") . DIRECTORY_SEPARATOR. $filenames;
      return (Storage::disk(config('ressources-file.disk'))->exists($path)); 
   }
   public static function  genererChaineAleatoire($longueur=15)
   {
      return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',ceil($longueur / strlen($x)))), 1, $longueur);
   }
   public static function removeSpeciauxCaractere($text=''){
       $search=array(' ','\/','#','â','Â','é','É','è','È','Ô','ô','Û','û','ù','!','@','%', '$', '{', '}', '(',')','Ø','[',']','^','', '~', '*', '<', '>', '?', ':', '|', '\\');
      //  $text = strlen($text) > 70 ? substr($text,-70) : $text;
       return str_replace($search,array('-','-','-','-','a','A','e','E','e','E','O','o','U','u','u','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-'),$text);
   }

   public static function GenerateZip($data)
   {     
         $zip=new ZipArchive();              
         $fileName = str_replace(' ',"-",$data['name']).'.zip';
        if ($zip->open(public_path("export-file/".$fileName), ZipArchive::CREATE) === TRUE)
        {   
            $files=array();
             foreach($data['fileIds'] as $value){
               $path=str_replace('storage/','',$value);
               if(Storage::disk(config('ressources-file.disk'))->exists($path)){
                  $path=storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.$value;
                  $files= array(...$files,$path);
               }
            }
            foreach ($files as $key => $value) {
                $file = basename($value);
                $zip->addFile($value, $file);
            }
            $zip->close();
         }
         return $fileName;
    }
    public static function removeFolderLocal($name='export-file',$disk='export'){
      if($name){
         Storage::disk($disk)->deleteDirectory($name);
      }
      Storage::disk($disk)->makeDirectory($name);
    }
}