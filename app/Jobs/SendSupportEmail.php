<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SupportRequestNotification;

class SendSupportEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $messageContent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $messageContent)
    {
        $this->email = $email;
        $this->messageContent = $messageContent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $adminEmails = explode(',', envparam('SUPPORT_EMAILS'));
        $validAdminEmails = array_filter($adminEmails, function ($email) {
            return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
        });

        foreach($validAdminEmails as $email)
        {
            Notification::route('mail', $email)->notify(new SupportRequestNotification("Bouncee: New Support Request", ['email' => $this->email, 'messageContent' => $this->messageContent], 'support-email'));

        }
    }
}
