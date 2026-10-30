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
            // $table->timestamps();
            $table->string('title', 255)->index();
            $table->text('description')->nullable();
            $table->dateTime('due_date');
            $table->dateTime('create_date');
            $table->string('status');
            $table->string('priority');
            $table->string('category');
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
