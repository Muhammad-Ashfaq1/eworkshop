<?php

namespace App\Jobs;

use App\Mail\ForgetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgetPasswordJob implements ShouldQueue
{
    use Queueable;

    public $user;
    public $token;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }
        //

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new ForgetPasswordMail($this->user, $this->token));
    }
}


