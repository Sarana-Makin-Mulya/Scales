<?php

namespace Modules\General\Tests\Feature\Unit;

use Tests\TestCase;
use Modules\General\Entities\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_store_new_unit_data()
    {
        $this->signIn();

        $response = $this->post(route('general.unit.store', [
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]));

        $response->assertOk();
        $response->assertExactJson([
            'message' => 'Berhasil menambahkan data satuan baru.',
        ]);

        $this->assertDatabaseHas('general_units', [
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]);
    }

    /** @test */
    public function guest_cannot_store_new_unit_data()
    {
        $this->withExceptionHandling();

        $this->post(route('general.unit.store', [
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_cannot_store_new_existing_unit_data()
    {
        $this->withExceptionHandling();

        $this->signIn();

        factory(Unit::class)->create([
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]);

        $response = $this->post(route('general.unit.store', [
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'symbol']);
    }

    /** @test */
    public function user_cannot_store_sotf_deleted_unit_data()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $unit = factory(Unit::class)->create([
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]);

        $unit->delete();

        $this->assertSoftDeleted('general_units', [
            'id' => $unit->id,
            'name' => $unit->name,
            'symbol' => $unit->symbol,
        ]);

        $response = $this->post(route('general.unit.store', [
            'name' => 'Kilogram',
            'symbol' => 'Kg',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'symbol']);
    }

    /** @test */
    public function user_cannot_store_unit_data_without_name()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $response = $this->post(route('general.unit.store', [
            'symbol' => 'Kg',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('general_units', ['symbol' => 'Kg']);
    }

    /** @test */
    public function user_cannot_store_unit_data_without_symbol()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $response = $this->post(route('general.unit.store', [
            'name' => 'Kilogram',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['symbol']);
        $this->assertDatabaseMissing('general_units', ['name' => 'Kilogram']);
    }
}
