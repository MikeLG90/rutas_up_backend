<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelo;


class ModeloController extends Controller
{
    public function index()
    {
        return Modelo::all();
    }

    public function store(Request $request)
    {
        $marca = Modelo::create($request->all());
        return response()->json($marca, 201);
    }

    public function show($id)
    {
        return Modelo::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $marca = Modelo::findOrFail($id);
        $marca->update($request->all());
        return response()->json($marca, 200);
    }

    public function destroy($id)
    {
        Marca::destroy($id);
        return response()->json(null, 204);
    }

    public function getModelosByMarca($marca_id)
    {
        $modelos = Modelo::where('marca_id', $marca_id)->get();
        return response()->json($modelos);
    }
}
