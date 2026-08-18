<?php

namespace App\Mail;

use App\Models\ParticipantReceipt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SouvenirReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ParticipantReceipt $receipt
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tanda Terima Pengambilan Souvenir',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.souvenir-receipt',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->path($this->receipt->photo)
            )
            ->as('Bukti_Penyerahan_Souvenir.jpg')
            ->withMime('image/jpeg'),
        ];
    }
}
