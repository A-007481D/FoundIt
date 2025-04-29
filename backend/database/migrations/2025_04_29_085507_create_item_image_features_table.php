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
        Schema::create('item_image_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('image_path');
            $table->json('feature_vector')->nullable();
            $table->json('classifications')->nullable();
            $table->string('category')->nullable();
            $table->string('color')->nullable();
            $table->string('brand')->nullable();
            $table->timestamps();
            
            // Add index for faster searches
            $table->index('item_id');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_image_features');
    }
};
