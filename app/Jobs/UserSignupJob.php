<?php

namespace App\Jobs;

use dispatchable;
use App\Constants\UserRoles;
use App\Mail\ActiveUserMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserSignupJob implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new ActiveUserMail($this->user));
        $this->user->assignRole(UserRoles::DEO);
    }
}
