<?php

namespace Modules\General\Tests\Feature\Unit;

use Tests\TestCase;
use Modules\General\Entities\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_update_unit_data()
    {
        $this->signIn();

        $unit = factory(Unit::class)->create([
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]);

        $this->assertDatabaseHas('general_units', [
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]);

        $this->assertDatabaseMissing('general_units', [
            'name' => 'Centimeter',
            'symbol' => 'Cm',
        ]);

        $response = $this->put(route('general.unit.update', [$unit->id]), [
            'name' => 'Centimeter',
            'symbol' => 'Cm',
        ]);

        $response->assertOk();
        $response->assertExactJson([
            'message' => 'Berhasil memperbaharui data satuan.',
        ]);

        $this->assertDatabaseHas('general_units', [
            'name' => 'Centimeter',
            'symbol' => 'Cm',
        ]);

        $this->assertDatabaseMissing('general_units', [
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]);
    }

    /** @test */
    public function guest_cannot_update_unit_data()
    {
        $this->withExceptionHandling();

        $unit = factory(Unit::class)->create();

        $this->put(route('general.unit.update', [$unit->id]))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_cannot_update_to_existing_unit_data()
    {
        $this->withExceptionHandling();

        $this->signIn();

        factory(Unit::class)->create([
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]);

        $cm = factory(Unit::class)->create([
            'name' => 'Centimeter',
            'symbol' => 'Cm',
        ]);

        $response = $this->put(route('general.unit.update', [$cm->id]), [
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'symbol']);
    }

    /** @test */
    public function user_cannot_update_to_existing_unit_data_except_his_data()
    {
        $this->withExceptionHandling();

        $this->signIn();

        factory(Unit::class)->create([
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]);

        $cm = factory(Unit::class)->create([
            'name' => 'Centimeter',
            'symbol' => 'Cm',
        ]);

        $response = $this->put(route('general.unit.update', [$cm->id]), [
            'name' => 'Centimeter',
            'symbol' => 'Cm',
        ]);

        $response->assertOk();
        $response->assertExactJson(['message' => 'Berhasil memperbaharui data satuan.']);
    }

    /** @test */
    public function user_cannot_update_unit_data_without_name()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $unit = factory(Unit::class)->create();

        $response = $this->put(route('general.unit.update', [$unit->id]), [
            'symbol' => 'new unit symbol',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('general_units', ['symbol' => 'new unit symbol']);
    }

    /** @test */
    public function user_cannot_update_unit_data_without_symbol()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $unit = factory(Unit::class)->create();

        $response = $this->put(route('general.unit.update', [$unit->id]), [
            'name' => 'new unit name',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['symbol']);

        $this->assertDatabaseMissing('general_units', ['name' => 'new unit name']);
    }
}
