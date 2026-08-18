<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('products', 'short_description')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('short_description');
            });
        }

        if (! Schema::hasColumn('product_variations', 'short_description')) {
            Schema::table('product_variations', function (Blueprint $table) {
                $table->string('short_description')->nullable()->after('name');
            });
        }

        $productIds = DB::table('products')->pluck('id');
        foreach ($productIds as $productId) {
            $defaultVariation = DB::table('product_variations')
                ->where('product_id', $productId)
                ->orderBy('id')
                ->first();

            if ($defaultVariation) {
                DB::table('product_variations')
                    ->where('id', $defaultVariation->id)
                    ->update([
                        'short_description' => DB::raw('COALESCE(short_description, "Premium product")'),
                    ]);
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'short_description')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('short_description')->nullable();
            });
        }

        if (Schema::hasColumn('product_variations', 'short_description')) {
            Schema::table('product_variations', function (Blueprint $table) {
                $table->dropColumn('short_description');
            });
        }
    }
};
