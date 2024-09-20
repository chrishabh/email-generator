<?php

namespace Database\Seeders;

use App\Models\EnvProperties as ModelsEnvProperties;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnvProperties extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') == "Production") {
            ModelsEnvProperties::truncate();
            DB::insert("INSERT INTO env_properties (`key`,value,disabled_YN,deleted_at,created_by,updated_by,created_at,updated_at) VALUES
            ('bouncify_key','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('debounce_key','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('RAZORPAY_KEY','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('RAZORPAY_SECRET','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('IS_BULK_EMAIL_VERIFIED_THROUGH_BOUNCIFY_BULK_API','0','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ");
        } 
        if (env('APP_ENV') == "local") {
            ModelsEnvProperties::truncate();
            DB::insert("INSERT INTO env_properties (`key`,value,disabled_YN,deleted_at,created_by,updated_by,created_at,updated_at) VALUES
            ('bouncify_key','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('debounce_key','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('RAZORPAY_KEY','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('RAZORPAY_SECRET','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('IS_BULK_EMAIL_VERIFIED_THROUGH_BOUNCIFY_BULK_API','0','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ");
        } 
        if (env('APP_ENV') == "staging") {
            ModelsEnvProperties::truncate();
            DB::insert("INSERT INTO env_properties (`key`,value,disabled_YN,deleted_at,created_by,updated_by,created_at,updated_at) VALUES
            ('bouncify_key','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('debounce_key','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('RAZORPAY_KEY','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('RAZORPAY_SECRET','','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ,('IS_BULK_EMAIL_VERIFIED_THROUGH_BOUNCIFY_BULK_API','0','N',NULL,1,NULL,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)
            ");
        } 
        
    }
}
