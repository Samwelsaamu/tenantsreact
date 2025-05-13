<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordChangedNotice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $fullname;

    public function __construct($fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('emails.sendpasswordchangednotice')->subject('Password Changed!!');
        return $this->markdown('emails.sendpasswordchangednotice')
            ->with(['fullname' => $this->fullname])
            ->subject('Password Changed!!');
    }
}
