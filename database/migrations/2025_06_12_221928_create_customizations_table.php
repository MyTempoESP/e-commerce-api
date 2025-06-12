<?php

use App\Models\Sku;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		// TODO: possibly make a new CUSTOMIZATION_PRODUCT table
		Schema::create('customizations', function (Blueprint $table) {
			$table->id();
			$table->string('name', 255);
			// possible 'restrictions'
			$table->string('options', 255)->nullable();
			// is user allowed to set this customization?
			$table->boolean('enabled');
			//$table->string('value', 255);
			$table->foreignIdFor(Sku::class)->constrained()->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('customizations');
	}
};
