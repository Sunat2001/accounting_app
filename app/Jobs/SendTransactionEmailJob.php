<?php

namespace App\Jobs;

use App\Mail\TransactionMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendTransactionEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $sendMail,
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new TransactionMail();

        Mail::to($this->sendMail)->send($email);
    }
}
