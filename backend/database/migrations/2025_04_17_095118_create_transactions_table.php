<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('buyer_position_id')->constrained('positions');
            $table->foreignId('seller_position_id')->constrained('positions');
            $table->decimal('amount', 10, 3);
            $table->unsignedBigInteger('price_per_gram');
            $table->unsignedBigInteger('fee');
            $table->unsignedBigInteger('total_payment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table): void {
            $table->dropForeign(['buyer_position_id']);
            $table->dropForeign(['seller_position_id']);
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('transactions');
    }
};
