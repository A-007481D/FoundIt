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
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->timestamp('email_change_allowed_after')->nullable();
            $table->timestamp('profile_updated_at')->nullable();
            $table->timestamp('last_profile_pic_change')->nullable();
            $table->json('notification_preferences')->nullable();
            $table->json('privacy_settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'bio',
                'profile_photo',
                'email_change_allowed_after',
                'profile_updated_at',
                'last_profile_pic_change',
                'notification_preferences',
                'privacy_settings'
            ]);
        });
    }
};
