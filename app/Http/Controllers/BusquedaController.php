<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Models\Evento;
use App\Models\Memorandum;
use App\Models\Persona;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q', '');

        if (trim($q) === '') {
            return back();
        }

        $term = '%' . $q . '%';
        $isNumeric = ctype_digit($q);

        $eventos = Evento::where('evento', 'ilike', $term)
            ->orWhere('detalle', 'ilike', $term)
            ->when($isNumeric, fn($query) => $query->orWhere('id', $q))
            ->limit(10)->get();

        $certificados = Certificado::where('nombre', 'ilike', $term)
            ->orWhere('paterno', 'ilike', $term)
            ->orWhere('materno', 'ilike', $term)
            ->orWhere('carnet', 'ilike', $term)
            ->when($isNumeric, fn($query) => $query->orWhere('id', $q)->orWhere('codigo', $q))
            ->limit(10)->get();

        $memorandums = Memorandum::where('evento', 'ilike', $term)
            ->orWhere('detalle', 'ilike', $term)
            ->orWhere('resolucion', 'ilike', $term)
            ->orWhere('carrera', 'ilike', $term)
            ->when($isNumeric, fn($query) => $query->orWhere('id', $q))
            ->limit(10)->get();

        $personas = Persona::where('nombre', 'ilike', $term)
            ->orWhere('paterno', 'ilike', $term)
            ->orWhere('materno', 'ilike', $term)
            ->orWhere('carnet', 'ilike', $term)
            ->when($isNumeric, fn($query) => $query->orWhere('id', $q))
            ->limit(10)->get();

        return view('buscar', compact('q', 'eventos', 'certificados', 'memorandums', 'personas'));
    }
}
