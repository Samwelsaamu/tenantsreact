<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendForgotPasswordCodeNotice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
        public $code;
        public $fullname;

        public function __construct($code,$fullname)
        {
            $this->code = $code;
            $this->fullname = $fullname;
        }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.sendforgotpasswordcodenotice')
            ->with(['code' => $this->code,'fullname' => $this->fullname])
            ->subject('Reset Password Code');
    }
}
