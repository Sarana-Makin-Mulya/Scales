<?php

namespace Modules\General\Tests\Feature\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_access_unit_index_page()
    {
        $this->signIn();

        $response = $this->get(route('general.unit.index'));

        $response->assertOk();
    }

    /** @test */
    public function unauthenticated_user_cannot_access_unit_index_page()
    {
        $this->withExceptionHandling();

        $this->get(route('general.unit.index'))
            ->assertRedirect(route('login'));
    }
}
