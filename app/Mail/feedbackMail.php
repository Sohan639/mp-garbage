<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class feedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $fromAddress;

    /**
     * Create a new message instance.
     *
     * @param  array  $data
     * @param  string  $fromAddress
     * @return void
     */
    public function __construct($data, $fromAddress,$subject)
    {
        $this->data = $data;
        $this->fromAddress = $fromAddress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->fromAddress)
                    ->view('emails.feedback')
                    ->with('data', $this->data)
                    ->subject($this->subject);
    }
}
