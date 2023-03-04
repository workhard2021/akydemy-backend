<?php

namespace Database\Seeders;

use App\Enums\eRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=eRole::getValues();
        foreach($data as $key =>$value){
            DB::table('roles')->insert(
                ['name'=>$value]
            );
        }
       
    }
}
