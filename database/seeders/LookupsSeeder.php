<?php

namespace Database\Seeders;

use App\Models\Lookup;
use Illuminate\Database\Seeder;

class LookupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lookup::truncate();
        $data =[
            ['lookup_type'=>'HMEV','lookup_code'=>'1_to_100_emails',       'lookup_text'=>'1 to 100 emails',       'sort_order'=>'1'],
            ['lookup_type'=>'HMEV','lookup_code'=>'100_to_5000_emails',    'lookup_text'=>'100 to 5000 emails',    'sort_order'=>'2'],
            ['lookup_type'=>'HMEV','lookup_code'=>'5000_to_50000_emails',  'lookup_text'=>'5000 to 50000 emails',  'sort_order'=>'3'],
            ['lookup_type'=>'HMEV','lookup_code'=>'50000_to_200000_emails','lookup_text'=>'50000 to 200000 emails','sort_order'=>'4' ],
            ['lookup_type'=>'HMEV','lookup_code'=>'200000_to_1M_emails',   'lookup_text'=>'200000 to 1M emails',   'sort_order'=>'5'],
            ['lookup_type'=>'HMEV','lookup_code'=>'More_than_1M_emails',   'lookup_text'=>'More than 1M emails',   'sort_order'=>'6'],
            
        ];
        // 
        Lookup::insert($data);
    }
}
