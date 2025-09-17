<?php

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excel_files', function (Blueprint $table) {
            $table->id();

            $table->string('file_name');
            $table->string('path');
            $table->string('driver')->default('local');

            $table->enum('status', array_column(ExcelFileStatus::cases(), 'value'))
                ->default(ExcelFileStatus::PENDING->value)
                ->index();

            $table->timestamp('extracted_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->json('meta')->nullable();
            $table->text('exception')->nullable();

            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('owner_type')->nullable();

            $table->timestamp('mapped_at')->nullable();
            $table->unsignedInteger('mapped_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excel_files');
    }
};
