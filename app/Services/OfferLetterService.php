<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OfferLetterService
{
    public function generateOfferLetter($employee)
    {
        try {
            $data = [
                'employee' => $employee,
                'date' => Carbon::now()->format('d F Y'),
                'company' => [
                    'name' => 'Vecura Wellness Clinic',
                    'address' => 'Vecura Wellness Clinic, Chennai',
                    'city' => 'Chennai',
                    'contact' => '+91-XXXX-XXXX-XX',
                ]
            ];

            $html = view('pdf.offer-letter', $data)->render();
            Log::info('PDF HTML rendered for employee: ' . $employee->UserCode);

            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');

            $filename = 'offer_letter_' . $employee->UserCode . '_' . time() . '.pdf';
            $path = 'offer_letters/' . $filename;

            $pdfContent = $pdf->output();
            Log::info('PDF generated, size: ' . strlen($pdfContent) . ' bytes');

            Storage::disk('local')->put($path, $pdfContent);
            Log::info('PDF stored to: ' . $path);

            // Use Storage::path() for proper path handling across platforms
            $fullPath = Storage::disk('local')->path($path);
            Log::info('Full path: ' . $fullPath);
            Log::info('File exists: ' . (file_exists($fullPath) ? 'YES' : 'NO'));

            if (!file_exists($fullPath)) {
                Log::error('File verification failed after Storage::put()');
            }

            return $fullPath;
        } catch (\Exception $e) {
            Log::error('Offer Letter Generation Error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return null;
        }
    }
}
