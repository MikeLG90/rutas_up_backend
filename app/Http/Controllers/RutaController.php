<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    /**
     * Mostrar todas las rutas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $rutas = Ruta::all(); 
        return response()->json($rutas);  
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $ruta = new Ruta();
        $ruta->fill($request->all()); 

        $ruta->save();

        return response()->json(['message' => 'Ruta creada con éxito.', 'ruta' => $ruta], 201); // Retornar la ruta creada como JSON
    }

    /**
     *
     * @param  \App\Models\Ruta  $ruta
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Ruta $ruta)
    {
        return response()->json($ruta); // Retornar la ruta específica como JSON
    }

    /**
     * Actualizar una ruta en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ruta  $ruta
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Ruta $ruta)
    {
        $ruta->fill($request->all()); // Asignar todos los datos de la solicitud al modelo

        $ruta->save(); // Guardar los cambios

        return response()->json(['message' => 'Ruta actualizada con éxito.', 'ruta' => $ruta]); // Retornar la ruta actualizada como JSON
    }

    /**
     * Eliminar una ruta de la base de datos.
     *
     * @param  \App\Models\Ruta  $ruta
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->delete(); // Eliminar la ruta

        return response()->json(['message' => 'Ruta eliminada con éxito.']); // Retornar un mensaje de éxito como JSON
    }
}
