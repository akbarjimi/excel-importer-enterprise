<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('excel_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('excel_file_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->unsignedInteger('rows_count')->default(0);
            $table->json('meta')->nullable();
            $table->timestamp('rows_extracted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excel_sheets');
    }
};
