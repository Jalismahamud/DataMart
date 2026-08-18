<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('regular_price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('size');
            $table->timestamps();
            $table->unique(['product_id', 'size']);
        });

        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('color');
            $table->timestamps();
            $table->unique(['product_id', 'color']);
        });

        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('short_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variations');
        Schema::dropIfExists('product_colors');
        Schema::dropIfExists('product_sizes');
        Schema::dropIfExists('products');
    }
};
