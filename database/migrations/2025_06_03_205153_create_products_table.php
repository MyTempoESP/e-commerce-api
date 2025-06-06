<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Category;
use App\Models\Shop;
use App\Models\Sku;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->string('name', 255);
			$table->string('description', 255)->nullable();
			$table->string('image', 255)->nullable();
			$table->decimal('price');
			$table->foreignIdFor(Category::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Shop::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Sku::class)->constrained()->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('products');
	}
};
