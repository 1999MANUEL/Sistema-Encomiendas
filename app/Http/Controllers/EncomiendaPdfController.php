<?php

namespace App\Http\Controllers;

use App\Models\Encomienda;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EncomiendaPdfController extends Controller
{
     public function show(Encomienda $encomienda)
    {
    $pdf = pdf::loadView('reportes.encomienda', compact('encomienda'));
return $pdf->stream('factura.pdf');
        return $pdf->stream('encomienda_'.$encomienda->id.'.pdf');
    }
}
