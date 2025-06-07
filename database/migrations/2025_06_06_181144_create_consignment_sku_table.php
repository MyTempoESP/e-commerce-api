<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Consignment;
use App\Models\Sku;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('consignment_sku', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Consignment::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Sku::class)->constrained()->onDelete('cascade');
			$table->decimal('price', 8, 2);
			$table->integer('quantity'); // deduct from Sku.quantity
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('consigment_sku');
	}
};
