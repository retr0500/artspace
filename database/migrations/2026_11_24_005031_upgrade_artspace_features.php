<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::table('artworks', function (Blueprint $table) {
            $table->string('tipo_archivo')->default('imagen')->after('imagen'); // 'imagen' o 'video'
            $table->timestamp('nuevo_hasta')->nullable()->after('destacado');
        });
    }

    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['tipo_archivo', 'nuevo_hasta']);
        });
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('categories');
    }
};