<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('slide_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presentation_month_id')->constrained()->cascadeOnDelete();
            $table->foreignId('slide_id')->constrained()->cascadeOnDelete();
            $table->json('values_json')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['presentation_month_id', 'slide_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slide_values');
    }
};
