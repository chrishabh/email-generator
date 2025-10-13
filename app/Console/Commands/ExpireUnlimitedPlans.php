<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserCredits;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireUnlimitedPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExpireUnlimitedPlans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire unlimited user plans whose validity period has ended.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "*******Start time************* " .date('Y-m-d h:i:s'); 
        echo "\n"; 

        $allUnlimitedUsers = UserCredits::getAllUnlimitedUsers();
        if(!$allUnlimitedUsers->isEmpty()){
            $count = 0;
            foreach($allUnlimitedUsers as $userValue){
                
                switch ($userValue['credits']) {
                    case '7 Days':
                        $expiryDate = Carbon::parse($userValue->created_at)->addDays(4);
                        break;
                    case '1 Month':
                        $expiryDate = Carbon::parse($userValue->created_at)->addMonth(1);
                        break;
                    case '3 Months':
                        $expiryDate = Carbon::parse($userValue->created_at)->addMonth(3);
                        break;
                    case '6 Months':
                        $expiryDate = Carbon::parse($userValue->created_at)->addMonth(6);
                        break;
                    default:
                        continue 2; // Skip to the next iteration if the plan is not recognized
                }
               

                if(Carbon::now() >= $expiryDate){
                    UserCredits::markExpiredUnlimitedPlan($userValue->user_id,$userValue->id);
                    echo "User ID {$userValue->user_id}, & ID {$userValue->id} unlimited plan expired.";
                    echo "\n"; 
                    $count++;
                }
                
            }
            echo "✅ {$count} unlimited plans expired successfully.";
            echo "\n"; 
        }else{
            echo "No unlimited plans found.";
            echo "\n"; 
        }
        echo "**************Completed Time***************************** " .date('Y-m-d h:i:s');
        echo "\n"; 
        return Command::SUCCESS;
        
        

        
    }
}
