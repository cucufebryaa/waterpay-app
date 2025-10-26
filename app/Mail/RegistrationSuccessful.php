<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Data registrasi (dari $validated).
     * @var array
     */
    public $data;

    /**
     * URL untuk halaman login.
     * @var string
     */
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @param array $data Data validasi dari form registrasi
     * @param string $loginUrl URL ke halaman login
     */
    public function __construct(array $data, string $loginUrl)
    {
        $this->data = $data;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Menentukan subjek email
        return new Envelope(
            subject: 'Pendaftaran Perusahaan Anda Berhasil',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Menentukan blade view mana yang akan digunakan untuk body email
        return new Content(
            view: 'emails.registration-successful',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
