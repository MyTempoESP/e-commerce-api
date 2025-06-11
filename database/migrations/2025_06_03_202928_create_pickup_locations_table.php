<?php

use App\Models\Address;
use App\Models\Shop;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('pickup_locations', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Address::class)->constrained();
			$table->foreignIdFor(Shop::class)->constrained()->onDelete('cascade');
			$table->string('name', 255);
			$table->string('slug', 255);
			$table->dateTime('pickup_at');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('pickup_locations');
	}
};
