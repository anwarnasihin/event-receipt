<?php

namespace App\Mail;

use App\Models\ParticipantReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Address;
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
        $location = $this->receipt->user->location ?? 'BINUS';

        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                'LSC BINUS ' . $location
            ),
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
            Attachment::fromData(
                fn () => Pdf::loadView(
                    'receipt.souvenir-pdf',
                    ['receipt' => $this->receipt]
                )
                ->setPaper('a4', 'portrait')
                ->output(),
                'Tanda_Terima_Souvenir.pdf'
            )
            ->withMime('application/pdf'),
        ];
    }
}
