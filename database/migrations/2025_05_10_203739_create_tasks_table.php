<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * nosotros en el controller solo estamos manejando, descripcion, titulo y el status.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('title');  // Task title
            $table->text('description')->nullable();  // Task description (nullable)
            $table->foreignId('project_id')->constrained()->onDelete('cascade');  // Foreign key to the projects table
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');  // User assigned to the task
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');  // Task status
            $table->integer('assigned_time')->nullable();  // Time estimated in minutes (nullable)
            $table->date('due_date')->nullable();  // Due date (nullable)
            $table->timestamps();  // Created_at and updated_at timestamps
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
