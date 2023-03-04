<?php

namespace App\Http\Controllers;

use App\Models\User;
class FastExcelController extends Controller
{
    public function subscription(User $user) {
        $name="export-file".DIRECTORY_SEPARATOR."abonnes.xlsx";
        if(!is_array(request()->moduleIds)){
            return null;
        }
        $data =$user->join('module_users','module_users.user_id','=','users.id')
        ->join('modules','modules.id','=','module_users.module_id')
        ->leftJoin('countries','countries.id','=','users.country')
        ->whereIn('module_users.module_id',request()->moduleIds)->get(['first_name as NOM','last_name as PRÉNOM','email as EMAIL','modules.title as SOUSCRIT','countries.name as PAYS','module_users.somme as PRIX']);
         fastexcel($data)->export(public_path($name));
        return $name;
    }
    public function all(User $user) {
        $name="export-file".DIRECTORY_SEPARATOR."utilisateurs.xlsx";
        $data =$user->leftJoin('countries','countries.id','=','users.country')
        ->get(['first_name as Nom ','last_name as Prénom','email','countries.name as Pays']);
        return response()->download(fastexcel($data)->export(public_path($name)));
    }
}