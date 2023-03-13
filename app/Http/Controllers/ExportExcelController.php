<?php

namespace App\Http\Controllers;
use App\Exports\SubscriptionExport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel; 

class ExportExcelController extends Controller
{
    public function subscription(User $user) {
        $name=config('ressources-file.export').DIRECTORY_SEPARATOR."abonnes.xlsx";
        if(!is_array(request()->suscriptionIds)){
            return null;
        }
        Excel::store(new SubscriptionExport(request()->suscriptionIds),$name,config('ressources-file.disk'));
        return $name;
    }
    public function all() {
        return null;
    }
}