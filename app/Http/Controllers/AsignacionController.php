<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignacion;
use Illuminate\Support\Facades\DB;


class AsignacionController extends Controller
{
    public function index()
    {

        $asignaciones = DB::table('rutas_up.choferes as c')
        ->join('rutas_up.usuarios as u', 'u.usuario_id', '=', 'c.usuario_id')
        ->join('rutas_up.personas as p', 'p.persona_id', '=', 'u.persona_id')
        ->join('rutas_up.asignaciones as a', 'c.id', '=', 'a.chofer_id')
        ->join('rutas_up.vehiculos as v', 'v.vehiculo_id', '=', 'a.vehiculo_id')
        ->join('rutas_up.marcas as mc', 'v.marca_id', '=', 'mc.marca_id')
        ->join('rutas_up.modelos as md', 'mc.marca_id', '=', 'md.marca_id')
        ->select(
            'a.*',
            DB::raw("COALESCE(p.nombre, '') || ' ' || COALESCE(p.ap_paterno, '') || ' ' || COALESCE(p.ap_materno, '') AS nombre_completo"),
            DB::raw("
                COALESCE(mc.marca, '') || ' - ' ||
                COALESCE(md.modelo, '') || ' - ' ||
                COALESCE(v.num_serie, '') || ' - ' ||
                COALESCE(v.placa, '') || ' - ' ||
                COALESCE(v.num_economico, '') || ' - ' ||
                COALESCE(v.anio::TEXT, '') AS vehiculo_detalles
            ")
        )
        ->get();
    
    
        return response()->json($asignaciones);
    }

    public function getVehiculos() {

        $vehiculos = DB::table('rutas_up.vehiculos as v')
        ->join('rutas_up.marcas as mc', 'v.marca_id', '=', 'mc.marca_id')
        ->join('rutas_up.modelos as md', 'mc.marca_id', '=', 'md.marca_id')
        ->select(
            'v.vehiculo_id',
            DB::raw("
                COALESCE(mc.marca, '') || ' - ' ||
                COALESCE(md.modelo, '') || ' - ' ||
                COALESCE(v.num_serie, '') || ' - ' ||
                COALESCE(v.placa, '') || ' - ' ||
                COALESCE(v.num_economico, '') || ' - ' ||
                COALESCE(v.anio::TEXT, '') AS vehiculo_detalles
            ")
        )
        ->get();
    
    
        return response()->json($vehiculos);
    }

    // Obtener una asignación por ID
    public function show($id)
    {
        $asignacion = Asignacion::find($id);
        return $asignacion ? response()->json($asignacion, 200) : response()->json(['error' => 'No encontrado'], 404);
    }

    public function store(Request $request)
    {
        $asignacion = Asignacion::create($request->all());
        return response()->json($asignacion, 201);
    }

    public function update(Request $request, $id)
    {
        $asignacion = Asignacion::find($id);
        if (!$asignacion) {
            return response()->json(['error' => 'No encontrado'], 404);
        }
        $asignacion->update($request->all());
        return response()->json($asignacion, 200);
    }

    public function destroy($id)
    {
        $asignacion = Asignacion::find($id);
        if (!$asignacion) {
            return response()->json(['error' => 'No encontrado'], 404);
        }
        $asignacion->delete();
        return response()->json(['message' => 'Eliminado'], 200);
    }
}

