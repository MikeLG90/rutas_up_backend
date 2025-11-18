<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Vehiculo;
use Mockery;

class VehiculoControllerTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_crear_un_vehiculo()
    {
        $vehiculoData = [
            'num_serie' => 'XYZ-987654',
            'marca_id' => 1,
            'modelo_id' => 1,
            'anio' => 2020,
            'placa' => 'ABC-123',
            'color' => 'Rojo',
            'tipo_combustible_id' => 1,
        ];

        $mock = Mockery::mock('overload:' . Vehiculo::class);
        $mock->shouldReceive('create')
             ->once()
             ->with($vehiculoData)
             ->andReturn((object)$vehiculoData);

        $response = $this->postJson('/api/vehiculos', $vehiculoData);

        $response->assertStatus(201);
        $response->assertJsonFragment(['placa' => 'ABC-123']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_listar_vehiculos()
    {
        $vehiculos = [
            (object)['vehiculo_id' => 1, 'placa' => 'ABC-123'],
            (object)['vehiculo_id' => 2, 'placa' => 'DEF-456'],
        ];

        $mock = Mockery::mock('overload:' . Vehiculo::class);
        $mock->shouldReceive('all')->once()->andReturn($vehiculos);

        $response = $this->getJson('/api/vehiculos');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['placa' => 'ABC-123']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_ver_un_vehiculo_por_id()
    {
        $vehiculo = (object)['vehiculo_id' => 1, 'placa' => 'ABC-123'];

        $mock = Mockery::mock('overload:' . Vehiculo::class);
        $mock->shouldReceive('findOrFail')->with(1)->once()->andReturn($vehiculo);

        $response = $this->getJson('/api/vehiculos/1');

        $response->assertStatus(200);
        $response->assertJsonFragment(['placa' => 'ABC-123']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_actualizar_un_vehiculo()
    {
        $vehiculo = Mockery::mock();
        $vehiculo->shouldReceive('update')->once()->with(['placa' => 'XYZ-999'])->andReturn(true);
        $vehiculo->placa = 'XYZ-999';

        $mock = Mockery::mock('overload:' . Vehiculo::class);
        $mock->shouldReceive('findOrFail')->with(1)->once()->andReturn($vehiculo);

        $response = $this->putJson('/api/vehiculos/1', ['placa' => 'XYZ-999']);

        $response->assertStatus(200);
        $response->assertJsonFragment(['placa' => 'XYZ-999']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_eliminar_un_vehiculo()
    {
        $vehiculo = Mockery::mock();
        $vehiculo->shouldReceive('delete')->once()->andReturn(true);

        $mock = Mockery::mock('overload:' . Vehiculo::class);
        $mock->shouldReceive('findOrFail')->with(1)->once()->andReturn($vehiculo);

        $response = $this->deleteJson('/api/vehiculos/1');

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Vehiculo eliminado']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
