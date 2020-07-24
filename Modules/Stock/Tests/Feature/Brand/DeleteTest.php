<?php

namespace Modules\Stock\Tests\Feature\Brand;

use Tests\TestCase;
use Modules\Stock\Entities\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_users_can_do_action_soft_delete_brand_data()
    {
        $this->signIn();

        $brand = factory(Brand::class)->create();

        $this->assertDatabaseHas('item_brands', [
            'name' => $brand->name,
            'slug' => $brand->slug,
        ]);

        $response = $this->delete(route('ajax.stock.item.destroy.brand', [$brand->id]));

        $response->assertOk();

        $response->assertExactJson([
            'message' => 'Berhasil menghapus data merek barang dengan nama "'. $brand->name .'".',
        ]);

        $this->assertSoftDeleted('item_brands', [
            'id' => $brand->id,
            'name' => $brand->name,
            'slug' => $brand->slug,
        ]);
    }

    /** @test */
    public function guest_cannot_do_action_soft_delete_brand_data()
    {
        $this->withExceptionHandling();

        $brand = factory(Brand::class)->create();

        $this->assertDatabaseHas('item_brands', [
            'name' => $brand->name,
            'slug' => $brand->slug,
        ]);

        $response = $this->delete(route('ajax.stock.item.destroy.brand', [$brand->id]));

        $response->assertRedirect(route('login'));
    }
}
