<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNewAccountCreatedNotice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $url;
    public $fullname;

    public function __construct($url,$fullname)
    {
        $this->url = $url;
        $this->fullname = $fullname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.sendnewaccountcreatednotice')
            ->with(['url' => $this->url, 'fullname' => $this->fullname])
            ->subject('New Account Created!!');
    }
}
