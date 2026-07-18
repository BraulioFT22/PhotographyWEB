<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('ip', 45); // 45 chars cubre IPv6
            $table->timestamps();

            // Un like por IP por foto
            $table->unique(['post_id', 'ip']);
        });

        // Agrega columna de contador a posts para no contar siempre en tiempo real
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('likes_count')->default(0)->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('likes_count');
        });
        Schema::dropIfExists('likes');
    }
};