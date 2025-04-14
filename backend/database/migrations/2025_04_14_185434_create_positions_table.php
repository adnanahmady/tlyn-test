<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('base_amount', 10, 3);
            $table->decimal('amount', 10, 3);
            $table->unsignedBigInteger('price_per_gram');
            $table->tinyInteger('type')->index();
            $table->tinyInteger('status')->index();
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::table(
            'positions',
            function (Blueprint $table): void {
                $table->dropForeign(['user_id']);
                $table->dropIndex(['type']);
                $table->dropIndex(['status']);
            },
        );
        Schema::dropIfExists('positions');
    }
};
