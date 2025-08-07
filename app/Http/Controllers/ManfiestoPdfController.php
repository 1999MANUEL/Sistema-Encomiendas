<?php

namespace App\Http\Controllers;

use App\Models\Manifiesto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ManfiestoPdfController extends Controller
{
public function show(Manifiesto $manifiesto)
{
    $manifiesto->load('encomiendas'); // <<--- carga las encomiendas asociadas

    $pdf = Pdf::loadView('reportes.manifiesto', compact('manifiesto'));
    return $pdf->stream('manifiesto_'.$manifiesto->id.'.pdf');
}

}
