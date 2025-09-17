<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanPenaltyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $book;
    public $dueDate;

    public function __construct($user, $book, $dueDate)
    {
        $this->user = $user;
        $this->book = $book;
        $this->dueDate = $dueDate;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Aviso de multa por préstamo vencido',
        );
    }

    public function content()
    {
        return new Content(
            view: 'loan_penalty', 
        );
    }

    public function attachments()
    {
        return [];
    }
}
