<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PersonasCsvMail extends Mailable
{
    use Queueable, SerializesModels;

    public $csvFileName;
    public $csvFilePath;

    /**
     * Create a new message instance.
     */
    public function __construct($csvFileName, $csvFilePath)
    {
        $this->csvFileName = $csvFileName;
        $this->csvFilePath = $csvFilePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Export CVS Persone')
            ->markdown('emails.personas_csv')
            ->attach($this->csvFilePath, [
                'as' => $this->csvFileName,
                'mime' => 'text/csv',
            ]);
    }
}
