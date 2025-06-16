<?php

use App\Models\Category;
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
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->uuid();

			$table->string('name', 255);

			$table->string('code', 255);
			$table->integer('quantity')->default(0);

			$table->decimal('price', 8, 2);
			$table->integer('discount');

			$table->json('desc_images')->nullable();
			$table->json('spec_images')->nullable();
			$table->json('pack_images')->nullable();
			
			$table->boolean('featured')->default(false);

			$table->string('image', 255)->nullable();
			$table->text('description')->nullable();

			$table->foreignIdFor(Shop::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Category::class)->constrained()->onDelete('cascade');
			$table->timestamps();

			$table->unique(['shop_id', 'code']);
			$table->unique('uuid');
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
