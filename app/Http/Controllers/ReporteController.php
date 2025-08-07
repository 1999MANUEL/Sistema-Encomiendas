<?php

namespace App\Http\Controllers;

use App\Models\Encomienda;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf; // Usa este
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function reporteEncomienda($id)
    {
        $encomienda = Encomienda::with('paquetes')->findOrFail($id);

        return Pdf::loadView('reportes.encomienda', compact('encomienda'))->stream('factura.pdf');
    }
}

