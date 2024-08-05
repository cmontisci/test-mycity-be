<?php

namespace App\Jobs;

use App\Mail\PersonasCsvMail;
use App\Models\Persona;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ExportPersonasToCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // TODO implementare filtri?
        $personas = Persona::all();

        // Create a CSV file
        $csvFileName = 'Export_persone_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $csvFilePath = storage_path('app/' . $csvFileName);
        $file = fopen($csvFilePath, 'w');

        // Add CSV headers
        fputcsv($file, ['ID', 'Nome', 'Cognome', 'Data di Nascita', 'Email', 'Telefono', 'Codice Fiscale']);

        // Add data rows
        foreach ($personas as $persona) {
            fputcsv($file, [
                $persona->id,
                $persona->nome,
                $persona->cognome,
                $persona->data_di_nascita,
                $persona->email,
                $persona->numero_di_telefono,
                $persona->codice_fiscale
            ]);
        }

        fclose($file);

        // Send email with the CSV attachment
        Mail::to('info@mycity.it')
            ->send(new PersonasCsvMail($csvFileName, $csvFilePath));

        // Delete the CSV file after sending the email
        Storage::delete($csvFileName);
    }
}
