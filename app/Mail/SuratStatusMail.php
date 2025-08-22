<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SuratPersetujuan;

class SuratStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $surat;
    public $statusMessage;
    public $statusColor;

    /**
     * Create a new message instance.
     */
    public function __construct(SuratPersetujuan $surat)
    {
        $this->surat = $surat;
        
        // Set message berdasarkan status
        switch ($surat->status) {
            case 'disetujui':
                $this->statusMessage = 'DISETUJUI';
                $this->statusColor = '#28a745';
                break;
            case 'ditolak':
                $this->statusMessage = 'DITOLAK';
                $this->statusColor = '#dc3545';
                break;
            case 'pending':
                $this->statusMessage = 'SEDANG DIPROSES';
                $this->statusColor = '#ffc107';
                break;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = "Update Status Surat - {$this->surat->nomor_surat}";
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.surat-status',
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
