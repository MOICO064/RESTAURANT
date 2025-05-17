<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('code', 100);
            $table->text('client_name');
            $table->float('amount', 15, 2)->default(0.00);
            $table->float('tendered', 15, 2)->default(0.00);
            $table->tinyInteger('payment_type')->comment('1 = Cash, 2 = Debit Card, 3 = Credit Card');
            $table->text('payment_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_list');
    }
};
