<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use Illuminate\Support\Facades\Storage;
use App\Models\Chofer; 
use App\Models\Vehiculo;

class DocumentoController extends Controller
{
public function index()
    {
        $documentos = Documento::with([
            'documentable' => function ($morphTo) {
                
                $morphTo->morphWith([
                    Chofer::class => ['usuario.persona'], 
                    Vehiculo::class => [],
                ]);
            }
        ])->get();
        
        return $documentos;
    }

    // guardar documentos
    public function store(Request $request) 
    {
        // valiadacion de datos enviados desede el Front End
        $request->validate([
                    'archivo' => 'required|file|mimes:pdf,jpg,png|max:5000',
                    'nombre' => 'required|string|max:255',
                    'fecha_expiracion' => 'nullable|date',
                    'documentable_id' => 'required|integer',
                    'documentable_type' => 'required|string|in:App\Models\Vehiculo,App\Models\Chofer',
        ]);
        // guardar documento
        $path = $request->file('archivo')->store('documentos', 'public');

        // crear registro en la bd
        $documento = Documento::create([
            'nombre' => $request->nombre,
            'fecha_expiracion' => $request->fecha_expiracion,
            'ruta_archivo' => $path,
            'documentable_id' => $request->documentable_id,
            'documentable_type' => $request->documentable_type,
        ]);

        return response()->json($documento->load('documentable'), 201);
    }

public function download(Documento $documento)
{

    if (is_null($documento->ruta_archivo)) {
        return response()->json([
            'message' => 'El registro del documento existe, pero la ruta de archivo es nula. Es posible que la subida haya fallado.',
            'documento_id' => $documento->id
        ], 404);
    }
    
    if (!Storage::disk('public')->exists($documento->ruta_archivo)) {
        return response()->json([
            'message' => 'El archivo no fue encontrado en el sistema de almacenamiento. El registro de la base de datos está roto.',
            'ruta_esperada' => $documento->ruta_archivo
        ], 404);
    }

    $extension = pathinfo($documento->ruta_archivo, PATHINFO_EXTENSION);
    $nombre_descarga = $documento->nombre . '.' . $extension;

    return Storage::disk('public')->download($documento->ruta_archivo, $nombre_descarga);
}

    // traer doc para poder mostrarlo
    public function view(Documento $documento) 
    {
        // valdiar si esta
        if(is_null($documento->ruta_archivo) || !Storage::disk('public')->exists($documento->ruta_archivo)) {
            return response()->json(['message' => 'Archivo no encontrado o ruta nula'], 404);
        }

        // traer si existe
        return Storage::disk('public')->response($documento->ruta_archivo);
    }
}
