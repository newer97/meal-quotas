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
        Schema::create('meal_serves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('served_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamp('served_at')->default(now());
            $table->enum('status', ['successful', 'failed']);
            $table->text('failure_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_serves');
    }
};
