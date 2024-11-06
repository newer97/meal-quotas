<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->foreignId('meal_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable();
            $table->foreignId('served_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamp('served_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
