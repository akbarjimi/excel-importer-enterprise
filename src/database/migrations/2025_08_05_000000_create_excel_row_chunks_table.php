<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excel_row_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('excel_sheet_id')->constrained()->cascadeOnDelete()->index();
            $table->unsignedBigInteger('from_row_id')->index();
            $table->unsignedBigInteger('to_row_id')->index();
            $table->unsignedInteger('size');
            $table->enum('status', ['pending', 'dispatching', 'queued', 'processing', 'completed', 'failed'])
                ->default('pending')->index();
            $table->unsignedInteger('attempts')->default(0);
            $table->text('error')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('processed_at')->nullable();

            $table->enum('mapping_status', ['pending', 'processing', 'completed', 'failed'])
                ->default('pending')->index();
            $table->timestamp('mapped_at')->nullable();

            $table->timestamps();

            $table->unique(['excel_sheet_id', 'from_row_id', 'to_row_id'], 'unique_sheet_row_range');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excel_row_chunks');
    }
};
