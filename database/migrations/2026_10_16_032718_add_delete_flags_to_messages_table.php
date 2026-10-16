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
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('deleted_for_everyone')->default(false)->after('read_at');
            $table->boolean('deleted_by_sender')->default(false)->after('deleted_for_everyone');
            $table->boolean('deleted_by_receiver')->default(false)->after('deleted_by_sender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['deleted_for_everyone', 'deleted_by_sender', 'deleted_by_receiver']);
        });
    }
};
