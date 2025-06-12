<?php

use App\Models\Consignee;
use App\Models\Shop;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('reports', function (Blueprint $table) {
			$table->id();
			$table->enum('report', [
				'Para Exposição',
				'Para Devolução'
			]);
			$table->enum('type', [
				'Reclamação',
				'Devolução',
				'Garantia',
				'Troca'
			]);
			$table->enum('priority', [
				'Baixa',
				'Média',
				'Alta'
			]);
			$table->text('description')->nullable();

			$table->foreignIdFor(Consignee::class)->constrained()->onDelete('cascade');
			$table->foreignIdFor(Shop::class)->constrained()->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('reports');
	}
};
