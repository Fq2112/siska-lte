<?php

namespace App\Listeners\Agencies;

use App\Events\Agencies\ApplicantList;
use App\Mail\Agencies\ApplicantListEmail;
use Illuminate\Support\Facades\Mail;

class SendApplicantList
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ApplicantList $event
     * @return void
     */
    public function handle(ApplicantList $event)
    {
        Mail::to($event->email)->send(new ApplicantListEmail($event->vacancy, $event->filename));
    }
}
