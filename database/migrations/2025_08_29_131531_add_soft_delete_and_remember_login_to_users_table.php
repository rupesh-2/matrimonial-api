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
            // Add remember login fields
            $table->boolean('remember_login')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('last_login_user_agent')->nullable();
            
            // Add account status
            $table->enum('status', ['active', 'suspended', 'deleted'])->default('active');
            $table->text('deletion_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'remember_login',
                'last_login_at',
                'last_login_ip',
                'last_login_user_agent',
                'status',
                'deletion_reason'
            ]);
        });
    }
};
