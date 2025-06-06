<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Address;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('shops', function (Blueprint $table) {
			$table->id();
			$table->string('name', 255);
			$table->string('manager_first_name', 255);
			$table->string('manager_last_name', 255);
			$table->string('phone', 255);
			//$table->string('image', 255)->nullable();
			// NOTE: 'document': cnpj
			//$table->string('document', 14);
			$table->foreignIdFor(Address::class)->constrained();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('shops');
	}
};
