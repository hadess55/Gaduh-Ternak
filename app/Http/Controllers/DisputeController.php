<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DisputeController extends Controller
{
    public function surat(Dispute $dispute)
    {
        $pdf = PDF::loadView('pdf.surat', compact('dispute'));
        return $pdf->download("Surat-{$dispute->id}.pdf");
    }
}
