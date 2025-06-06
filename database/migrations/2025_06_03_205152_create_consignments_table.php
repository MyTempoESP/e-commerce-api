<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Consignee;
use App\Models\Shop;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('consignments', function (Blueprint $table) {
			$table->id();
			$table->string('slug', 255); // autogerado
			$table->decimal('fixed_commission', 8, 2)->default('0.00');
			$table->decimal('variable_commission', 8, 2)->default('0.00');
			$table->enum('status', [
				'rascunho',
				'pendente separação',
				'expedição/envio',
				'em trânsito',
				'dest. conferido'
			])->default('rascunho');
			$table->
			$table->foreignIdFor(Consignee::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Shop::class)->constrained();
			$table->timestamps();
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
