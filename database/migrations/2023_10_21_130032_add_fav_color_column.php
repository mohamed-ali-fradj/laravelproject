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
        Schema::table('users',function($table)  {
            $table->string('favoriteColor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::table('users',function($table) {
            $table->$table->dropColumn('favoriteColor');
        });
    }
};