<?php

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('excel_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('path');
            $table->string('driver')->default('local');
            $table->enum('status', array_column(ExcelFileStatus::cases(), 'value'))->default(ExcelFileStatus::PENDING->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excel_files');
    }
};
