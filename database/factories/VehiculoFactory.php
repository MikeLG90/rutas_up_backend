<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Vehiculo;

class VehiculoFactory extends Factory
{
    protected $model = Vehiculo::class;

    public function definition(): array
    {
        return [
            'num_serie' => $this->faker->unique()->regexify('[A-Z]{3}-\d{6}'),
            'marca_id' => 1,   // Ajusta según tu tabla de marcas
            'modelo_id' => 1,  // Ajusta según tu tabla de modelos
            'anio' => $this->faker->numberBetween(2000, 2025),
            'placa' => $this->faker->unique()->bothify('???-###'),
            'color' => $this->faker->safeColorName(),
            'tipo_combustible_id' => 1, // Ajusta según tu tabla de tipos de combustible
        ];
    }
}
