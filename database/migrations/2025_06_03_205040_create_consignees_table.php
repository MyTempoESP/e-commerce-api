<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Shop;
use App\Models\Address;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('consignees', function (Blueprint $table) {
			$table->id();
			$table->string('name', 255);
			$table->string('phone', 255);
			$table->foreignIdFor(Shop::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Address::class)->constrained();
			$table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('consignees');
	}
};
