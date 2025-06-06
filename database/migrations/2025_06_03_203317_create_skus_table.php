<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Shop;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('skus', function (Blueprint $table) {
			$table->id();
			$table->string('code', 255);

			$table->foreignIdFor(Shop::class)->constrained()->onDelete('cascade');
			$table->timestamps();

			$table->unique(['shop_id', 'code']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('skus');
	}
};
