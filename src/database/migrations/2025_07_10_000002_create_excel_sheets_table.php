<?php

use Akbarjimi\ExcelImporter\Enums\ExcelSheetStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excel_sheets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('excel_file_id')
                ->constrained()
                ->onDelete('cascade')
                ->index();

            $table->string('name');

            $table->enum('status', array_column(ExcelSheetStatus::cases(), 'value'))
                ->default(ExcelSheetStatus::PENDING->value)
                ->index();

            $table->unsignedInteger('rows_count')->nullable();
            $table->timestamp('rows_extracted_at')->nullable();
            $table->index(['excel_file_id', 'rows_extracted_at']);

            $table->unsignedInteger('chunk_count')->default(0);

            $table->unsignedInteger('processed_chunks')->default(0);

            $table->unsignedInteger('mapped_count')->default(0);
            $table->timestamp('mapped_at')->nullable();

            $table->json('meta')->nullable();
            $table->text('exception')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excel_sheets');
    }
};
