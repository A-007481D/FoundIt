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
        Schema::table('users', function (Blueprint $table) {
            // Suspension fields
            $table->text('suspended_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('suspension_end')->nullable();
            
            // Ban fields
            $table->text('banned_reason')->nullable();
            $table->timestamp('banned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop suspension fields
            $table->dropColumn('suspended_reason');
            $table->dropColumn('suspended_at');
            $table->dropColumn('suspension_end');
            
            // Drop ban fields
            $table->dropColumn('banned_reason');
            $table->dropColumn('banned_at');
        });
    }
};
