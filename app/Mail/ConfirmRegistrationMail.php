<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Hashids\Hashids;
use App\Models\Organization;

class ConfirmRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $organization;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $hashids    = new Hashids('secretkey', 12);
        $trimDash   = bin2hex($this->organization->uuid);
        $verifyLink = config('app.APP_GATEWAY_URL') . '/verify-organization/' . $hashids->encodeHex($trimDash);

        return $this->subject('Verify Organization')
            ->with([ 'verify_link' => $verifyLink ])
            ->markdown('emails.confirmRegistrationMail');
    }
}
