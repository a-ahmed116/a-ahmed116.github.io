<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use mikehaertl\pdftk\Pdf;
use TCPDF;
use setasign\Fpdi\PDFParser\PDFParser;
use setasign\Fpdi\Fpdi;

class PdfController extends Controller
{
    public function showPdf()
    {
//        $pdf = new Pdf(public_path('sample-protected.pdf'));
//        $pdf->setPassword('*A14M#&5&!@bU7mV');
//
//        try {
//            $pdfData = $pdf->getData();
//        } catch (\Exception $e) {
//            Log::error('Failed toload PDF file: ' . $e->getMessage());
//            abort(500, 'Failed to load PDF file.');
//        }
//
//        $headers = [
//            'Content-Type' => 'application/pdf',
//            'Content-Disposition' => 'inline; filename=sample-protected.pdf',
//            'Content-Length' => strlen($pdfData)
//        ];
//
//
//
//        return response($pdfData,200, $headers);


        $pdf = new Fpdi();
        $pdf->setSourceFile(public_path('s.pdf'));
        $pageCount = $pdf->setSourceFile(public_path('s.pdf'));
//        $pdf->setPassword('*A14M#&5&!@bU7mV');

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx);
        }

        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="filename.pdf"'
        ]);
    }
}
