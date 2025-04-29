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
        Schema::table('item_image_features', function (Blueprint $table) {
            $table->string('phash')->nullable()->after('brand');
            $table->index('phash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_image_features', function (Blueprint $table) {
            $table->dropIndex(['phash']);
            $table->dropColumn('phash');
        });
    }
};
