<?php

namespace App\Jobs;

use App\Mail\RentalOverdueEmailNotification;
use App\Models\Book;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OverDueRentalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $rental_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rental_id)
    {
        $this->rental_id = $rental_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rental = Rental::where('id', $this->rental_id)->first();

        if ($rental->return_date === null) {
            $rental->is_due = true;

            // send email notification to user
            Mail::to($rental->user->email)->send(new RentalOverdueEmailNotification($rental->load("book")->load("user")));
        }
    }
}
