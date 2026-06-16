<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE projects
            MODIFY description TEXT
        ");

        DB::statement("
            ALTER TABLE tasks
            MODIFY description TEXT
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE projects
            MODIFY description VARCHAR(255)
        ");

        DB::statement("
            ALTER TABLE tasks
            MODIFY description VARCHAR(255)
        ");
    }
};
