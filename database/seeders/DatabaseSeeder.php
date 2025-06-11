<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		// User::factory(10)->create();

		User::factory()->create([
			'name' => 'admin',
			'email' => '1@1.1',
			'email_verified_at' => now(),
			'password' => Hash::make('1')
		]);

		Administrator::create([
			'user_id' => User::first()->id
		]);
	}
}
