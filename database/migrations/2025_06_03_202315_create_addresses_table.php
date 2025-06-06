<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('addresses', function (Blueprint $table) {
			$table->id();
			$table->string('street_address', 255);
			$table->string('locality', 255); // town/city
			$table->string('region', 255); // state, province, county, etc
			$table->string('postal_code', 255);
			$table->string('country', 2); // ISO code
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('addresses');
	}
};
