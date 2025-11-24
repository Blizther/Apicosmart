<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailApicoSmart extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $url;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->url = route('verify.email', ['token' => $user->verification_token]);
    }

    public function build()
    {
        return $this->subject('Verifica tu correo en ApicoSmart')
            ->view('emails.verify-email')
            ->with([
                'user' => $this->user,
                'url'  => $this->url,
            ]);
    }
}
