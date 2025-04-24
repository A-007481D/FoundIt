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
        Schema::create('item_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lost_item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('found_item_id')->constrained('items')->onDelete('cascade');
            $table->float('score');
            $table->timestamps();
            $table->unique(['lost_item_id', 'found_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_matches');
    }
};
