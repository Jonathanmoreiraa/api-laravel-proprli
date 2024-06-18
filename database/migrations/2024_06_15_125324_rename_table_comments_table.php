<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Update column name "data" to "date" in comments table.
     */
    public function up(): void
    {
        if (Schema::hasColumn('comments', 'data')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->renameColumn('data', 'date');
            });
        }
    }
};
