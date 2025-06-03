<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
	/**
	 * A basic feature test example.
	 */
	public function test_get_products_endpoint(): void
	{
		$response = $this->get(uri: '/produtos');
		$response->assertStatus(status: 200);
	}
}
