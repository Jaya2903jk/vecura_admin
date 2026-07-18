<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Log;

class EmployeeWelcomeMail extends Mailable
{

    public $employee;
    public $offerLetterPath;

    public function __construct($employee, $offerLetterPath = null)
    {
        $this->employee = $employee;
        $this->offerLetterPath = $offerLetterPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->employee->EmailId,
            subject: 'Welcome to Vecura Wellness Clinic - Employment Offer Letter',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.employee-welcome',
            with: [
                'employee' => $this->employee,
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if (!$this->offerLetterPath) {
            Log::warning('No offer letter path provided for employee: ' . $this->employee->UserCode);
            return $attachments;
        }

        if (!file_exists($this->offerLetterPath)) {
            Log::warning('Offer letter file not found at: ' . $this->offerLetterPath);
            return $attachments;
        }

        Log::info('Attaching PDF from: ' . $this->offerLetterPath . ' (size: ' . filesize($this->offerLetterPath) . ' bytes)');

        $attachments[] = Attachment::fromPath($this->offerLetterPath)
            ->as('Offer_Letter_' . $this->employee->UserCode . '.pdf')
            ->withMime('application/pdf');

        Log::info('Attachment added successfully');

        return $attachments;
    }
}
