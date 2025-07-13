<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('excel_row_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('excel_row_id')->constrained()->onDelete('cascade');
            $table->json('messages');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excel_row_errors');
    }
};
