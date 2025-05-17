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
        Schema::create('product_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category_list')->onDelete('cascade');
            $table->text('name');
            $table->text('description');
            $table->float('price', 15, 2)->default(0.00);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_flag')->default(0);
            $table->timestamps();
        });       

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_list');
    }
};
