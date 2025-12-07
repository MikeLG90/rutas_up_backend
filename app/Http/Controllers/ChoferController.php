<?php

namespace App\Http\Controllers;
use App\Models\Chofer;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class ChoferController extends Controller
{
    public function index()
    {
        $choferes = DB::table('rutas_up.choferes as c')
        ->join('rutas_up.usuarios as u', 'u.usuario_id', '=', 'c.usuario_id')
        ->join('rutas_up.personas as p', 'p.persona_id', '=', 'u.persona_id')
        ->select(
            'c.id',
            'c.usuario_id',
            DB::raw("p.nombre || ' ' || p.ap_paterno || ' ' || p.ap_materno AS nombre_completo"),
            'c.licencia_conducir',
            'c.fecha_expiracion_licencia',
            'c.estatus',
            'c.fecha_ingreso',
            'c.observaciones'
        )
        ->get();

        return $choferes;
    }

    public function store(Request $request) 
    {
        $chofer = Chofer::create($request->all());
        return response()->json($chofer, 200);
    }

    public function update(Request $request, $id)
    {
        $chofer = Chofer::findOrFail($id);
        $chofer->update($request->all());
        return response()->json($chofer, 200);
    }
    public function destroy($id)
    {
        $chofer = Chofer::find($id);
        if (!$chofer) {
            return response()->json(['message' => 'Chofer no encontrado'], 404);
        }
        
        $chofer->delete();
        return response()->json(['message' => 'Chofer eliminado con éxito'], 200);
    }
    
     public function getChoferes() {
        $choferes = DB::table('rutas_up.usuarios as u')
        ->join('rutas_up.personas as p', 'p.persona_id', '=', 'u.persona_id')
        ->select(
            'u.usuario_id',
            DB::raw("p.nombre || ' ' || p.ap_paterno || ' ' || p.ap_materno AS nombre_completo")
        )
        ->where('u.rol_id', 3)
        ->get();

        return $choferes;
     }
}
