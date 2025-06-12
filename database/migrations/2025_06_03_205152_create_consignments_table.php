<?php

use App\Models\PickupLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Consignee;
use App\Models\Address;
use App\Models\Shop;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('consignments', function (Blueprint $table) {
			$table->id();
			$table->uuid();
			//$table->string('name', 255);
			$table->string('slug', 255); // autogerado
			$table->decimal('commission', 8, 2)->default('0.00');
			$table->enum('commission_type', [
				'fixed',
				'variable'
			])->default('fixed');
			$table->enum('status', [
				'rascunho',
				'pendente separação',
				'expedição/envio',
				'em trânsito',
				'dest. conferido'
			])->default('rascunho');
			$table->foreignIdFor(Shop::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Consignee::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(PickupLocation::class)->constrained();
			$table->timestamps();

			$table->unique('uuid');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('consignments');
	}
};
