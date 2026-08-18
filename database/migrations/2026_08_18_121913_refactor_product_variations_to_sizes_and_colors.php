<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove variation_id from orders
        if (Schema::hasColumn('orders', 'variation_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['variation_id']);
                $table->dropColumn('variation_id');
            });
        }

        Schema::dropIfExists('product_variations');

        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
        Schema::dropIfExists('product_colors');

        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        if (!Schema::hasColumn('orders', 'variation_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('variation_id')->nullable()->constrained('product_variations')->nullOnDelete();
            });
        }
    }
};
