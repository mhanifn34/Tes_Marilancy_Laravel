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
       Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        // Relasi ke job yang statusnya sudah 'on_progress'
        $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
        $table->string('task_name');
        // Status: to_do, doing, done (mirip sprint backlog sesuai proposal)
        $table->enum('status', ['to_do', 'doing', 'done'])->default('to_do');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
