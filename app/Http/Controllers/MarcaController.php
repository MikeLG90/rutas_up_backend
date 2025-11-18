<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;


class MarcaController extends Controller
{
    public function index()
    {
        return Marca::all();
    }

    public function store(Request $request)
    {
        $marca = Marca::create($request->all());
        return response()->json($marca, 201);
    }

    public function show($id)
    {
        return Marca::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $marca = Marca::findOrFail($id);
        $marca->update($request->all());
        return response()->json($marca, 200);
    }

    public function delete($id)
    {
        Marca::destroy($id);
        return response()->json(null, 204);
    }

    
}
