<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Consignment;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('consignment_product', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Consignment::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Product::class)->constrained()->onDelete('cascade');
			$table->decimal('price', 8, 2);
			$table->integer('quantity'); // deduct from Product.quantity
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('consigment_product');
	}
};
