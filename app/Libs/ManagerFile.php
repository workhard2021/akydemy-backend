<?php
namespace App\Libs;
use Illuminate\Support\Facades\Storage;

class ManagerFile {
   public static function upload($arrayFileName, $folder,$file_name=null,$identify=null){
      $url = $folder . DIRECTORY_SEPARATOR;
      $identify=$identify?$identify: self::genererChaineAleatoire();
      $name =  $identify . '_' . $arrayFileName->getClientOriginalName();
      $name = $file_name ? $file_name .'.'. $arrayFileName->getClientOriginalExtension() : $name;
      $name= self::removeSpeciauxCaractere($name);
      $arrayFileName->storeAs($url,$name,config('file-image.disk'));
      return $name;
   }
   public static function  delete($file_names,$folder){
      $paths=[];$count=0;
      if(is_array($file_names)) {
         foreach ($file_names as $value) {
            $path=$folder.DIRECTORY_SEPARATOR.$value;
            if (Storage::disk(config('file-image.disk'))->exists($path)) {
               $paths[]=$path;
            }
            $count++;
         }
         Storage::disk(config('file-image.disk'))->delete($paths);
         return $count;
      }
      $path= $folder . DIRECTORY_SEPARATOR . $file_names;
      if(Storage::disk(config('file-image.disk'))->exists($path)){
         return Storage::disk(config('file-image.disk'))->delete($path);
      }
      return null;
   }
   
   public static function getFile($file_name,$folder){
      $path=$folder.DIRECTORY_SEPARATOR;
      if(Storage::disk(config('file-image.disk'))->exists($path.$file_name)){
        return Storage::disk(config('file-image.disk'))->get($path.$file_name);
      }
      $path=config('file-image.default-image');
      return Storage::disk(config('file-image.disk'))->get($path);
   }
   public static function deplacerFile($filenames,$folder){
      $newArray=[];
      $from = config("file-image.tmp").DIRECTORY_SEPARATOR;
      $to = $folder.DIRECTORY_SEPARATOR;
      if(!is_array($filenames)){
         $f = $from . $filenames;
         $t = $to . $filenames;
         if(Storage::disk(config('file-image.disk'))->exists($f)) {
            $file= $filenames;
            Storage::disk(config('file-image.disk'))->move($f,$t);
         }
         return $file;
      }
      if($folder && $filenames){
         foreach($filenames as $value){
            $f = $from . $value;
            $t = $to . $value;
            if(Storage::disk(config('file-image.disk'))->exists($f)){
               $newArray[]=$value;
               Storage::disk(config('file-image.disk'))->move($f,$t);
            }
            $f = $t = null;
         }
      }
      return $newArray;
   }
   public static function deplacerFileExcept($file,$from,$to)
   {     
         $from = config("file-image.$from") . DIRECTORY_SEPARATOR . $file;
         $to = config("file-image.$to") . DIRECTORY_SEPARATOR . $file;
         if(Storage::disk(config('file-image.disk'))->exists($from)) {
            if(!Storage::disk(config('file-image.disk'))->exists($to)){
                 Storage::disk(config('file-image.disk'))->copy($from, $to);
            }
            return true;
         }
         return false;
   }

   public static function exist($filenames,$folder)
   {
      $newArray = [];
      $path = config("file-image.$folder") . DIRECTORY_SEPARATOR;
      if (!is_array($filenames)) {
         $filenames=[$filenames];
      }
      if($folder && $filenames) {
         foreach ($filenames as $value) {
            $f = $path . $value;
            if (Storage::disk(config('file-image.disk'))->exists($f)) {
               $newArray[] = $value;
            }
            $f = null;
         }
      }
      return $newArray;
   }
   public static function isExist($filenames, $folder)
   {
      $path = config("file-image.$folder") . DIRECTORY_SEPARATOR. $filenames;
      return (Storage::disk(config('file-image.disk'))->exists($path)); 
   }
   public static function  genererChaineAleatoire($longueur=20)
   {
      return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',ceil($longueur / strlen($x)))), 1, $longueur);
   }
   public static function removeSpeciauxCaractere($text=''){
       $search=array(' ','\/','!','@','%', '$', '{', '}', '(',')','Ø','[',']','^','', '~', '*', '<', '>', '?', ':', '|', '\\');
       $text = strlen($text) > 70 ? substr($text, -70) : $text;
       return str_replace($search,'',$text);
   }
}