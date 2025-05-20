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
        Schema::table('users', function (Blueprint $table) {
            // Tambah baris ini:
            $table->string('role')->default('staf'); // Pilihan lain: ->nullable() jika tak nak default
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           // Tambah baris ini untuk buang kolum jika rollback
            $table->dropColumn('role');
        });
    }
};
