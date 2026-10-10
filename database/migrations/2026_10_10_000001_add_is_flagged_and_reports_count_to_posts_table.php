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
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_flagged')->default(false)->after('image_path');
            $table->unsignedInteger('reports_count')->default(0)->after('is_flagged');

            $table->index('is_flagged');
            $table->index('reports_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['is_flagged']);
            $table->dropIndex(['reports_count']);
            $table->dropColumn(['is_flagged', 'reports_count']);
        });
    }
};
