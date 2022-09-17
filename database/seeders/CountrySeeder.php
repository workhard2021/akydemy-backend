<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries=['Mali','Guinée','Sénégal','Portugal'];
        foreach($countries as $value){
                $data[]=['name'=>$value];
        }
        DB::table('countries')->insert($data);
    }
}
