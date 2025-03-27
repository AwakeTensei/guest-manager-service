<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id(); // Первичный ключ
            $table->string('first_name'); // Имя (обязательное)
            $table->string('last_name'); // Фамилия (обязательное)
            $table->string('email')->unique()->nullable(); // Email (уникальный, необязательный)
            $table->string('phone')->unique(); // Телефон (уникальный, обязательный)
            $table->string('country')->nullable(); // Страна (необязательная)
            $table->timestamps(); // created_at и updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};