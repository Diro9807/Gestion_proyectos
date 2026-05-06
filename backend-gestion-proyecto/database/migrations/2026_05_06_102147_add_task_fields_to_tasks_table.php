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
        Schema::table('tasks', function (Blueprint $table) {
            $table->text('description')->nullable();

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();

            $table->date('due_date')->nullable();

            $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'start_date',
                'end_date',
                'due_date',
                'status'
            ]);
        });
    }
};
