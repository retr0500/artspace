<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('password'); // 'admin' o 'user'
            $table->boolean('blocked')->default(false)->after('role');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->text('respuesta')->nullable()->after('mensaje');
            $table->timestamp('respondido_at')->nullable()->after('respuesta');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'respuesta', 'respondido_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'blocked']);
        });
    }
};