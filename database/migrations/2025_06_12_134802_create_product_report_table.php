<?php

use App\Models\Product;
use App\Models\Report;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('product_report', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Product::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Report::class)->constrained()->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('sku_report');
	}
};
