<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehiculo;

class VehiculoController extends Controller
{
    public function index()
    {
        return Vehiculo::with(['marca', 'modelo'])->get();
    }

    public function store(Request $request)
    {
        // crear vehiculo combi
        $vehiculo = Vehiculo::create($request->all());

        // regresar la respuesta como json
        return response()->json($vehiculo, 201);
    }


    public function show($id)
    {
        return Vehiculo::with(['marca', 'modelo'])->findOrFail($id);
    }
    public function update(Request $request, $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        $vehiculo->update($request->all());

        return response()->json($vehiculo, 200);
    }


    public function destroy($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->delete();
        return response()->json(null, 204);
    }

    public function mapa()
    {
        return view('map');
    }
}
