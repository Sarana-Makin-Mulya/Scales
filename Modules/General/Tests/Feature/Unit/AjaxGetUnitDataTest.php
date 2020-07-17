<?php

namespace Modules\General\Tests\Feature\Unit;

use Tests\TestCase;
use Modules\General\Entities\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AjaxGetUnitDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_get_unit_data()
    {
        $this->signIn();

        factory(Unit::class, 25)->create();

        $response = $this->get(route('ajax.general.get.unit'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'symbol',
                    'description',
                    'status',
                    'url_edit',
                    'url_status_update',
                ],
            ],
        ]);
    }

    /** @test */
    public function authenticated_user_exatly_just_get_unsoft_deleted_unit_data()
    {
        $this->signIn();
        factory(Unit::class, 6)->create();
        $unit = factory(Unit::class)->create();
        $unit->delete();

        $response = $this->get(route('ajax.general.get.unit'));

        $response->assertOk();

        $response->assertJsonMissing([
            'id' => $unit->id,
            'name' => $unit->name,
            'symbol' => $unit->symbol,
        ]);
    }

    /** @test */
    public function guest_user_cannot_get_unit_data()
    {
        $this->withExceptionHandling();
        $this->get(route('ajax.general.get.unit'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_filter_how_much_data_showing_per_page()
    {
        $this->signIn();

        factory(Unit::class, 50)->create();

        $response = $this->get(route('ajax.general.get.unit', [ 'per_page' => 15 ]));

        $response->assertOk();

        $response->assertJsonCount(15, 'data');
    }

    /** @test */
    public function user_can_search_unit_data_by_name_as_keyword()
    {
        $this->signIn();

        $kg = factory(Unit::class)->create([ 'name' => 'Kilogram' ]);
        $kgJoule = factory(Unit::class)->create([ 'name' => 'Kilo Joule' ]);
        factory(Unit::class)->create([ 'name' => 'Centimeter' ]);
        factory(Unit::class)->create([ 'name' => 'Galon' ]);

        $response = $this->get(route('ajax.general.get.unit', [ 'keyword' => 'kilo' ]));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                [
                    'id' => $kgJoule->id,
                    'name' => $kgJoule->name,
                    'symbol' => $kgJoule->symbol,
                    'description' => $kgJoule->description,
                    'status' => $kgJoule->is_active,
                    'url_edit' => route('general.unit.update', [$kgJoule->id]),
                    'url_status_update' => route('general.unit.update.status', [$kgJoule->id]),
                ],
                [
                    'id' => $kg->id,
                    'name' => $kg->name,
                    'symbol' => $kg->symbol,
                    'description' => $kg->description,
                    'status' => $kg->is_active,
                    'url_edit' => route('general.unit.update', [$kg->id]),
                    'url_status_update' => route('general.unit.update.status', [$kg->id]),
                ],
            ],
            'meta' => [],
            'links' => [],
        ]);
    }
}
