<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('excel_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('excel_sheet_id')->constrained()->onDelete('cascade');
            $table->json('content');
            $table->boolean('is_processed')->default(false);
            $table->string('content_hash')->nullable()->unique();
            $table->unsignedInteger('chunk_index')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excel_rows');
    }
};
