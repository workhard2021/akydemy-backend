<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   $categories=['php avancé','auto-cad débutant','java avancé','auto-cad avance'];
        $data=[];
        foreach($categories as $value){
                $data[]=['name'=>$value];
        }
        DB::table('categories')->insert($data);
    }
}
