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
        Schema::create('staff_details', function (Blueprint $table) {
        $table->id(); // Primary key untuk jadual staff_details
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key ke jadual users, jika user dipadam, rekod ini juga.
        $table->string('staff_id_number')->unique()->nullable(); // ID Staf (unik, boleh jadi nullable jika dijana kemudian atau tak wajib)
        $table->string('department')->nullable(); // Jabatan
        $table->string('position')->nullable(); // Jawatan
        $table->string('phone_number')->nullable(); // No. Telefon Staf
        $table->text('address')->nullable(); // Alamat Rumah
        $table->date('date_joined')->nullable(); // Tarikh Mula Kerja
        $table->decimal('salary', 10, 2)->nullable(); // Gaji (cth: 99999999.99)
        $table->string('bank_name')->nullable(); // Nama Bank
        $table->string('bank_account_number')->nullable(); // Nombor Akaun Bank
        $table->string('emergency_contact_name')->nullable(); // Nama Kenalan Kecemasan
        $table->string('emergency_contact_phone')->nullable(); // No. Telefon Kenalan Kecemasan
        $table->string('profile_image_path')->nullable(); // Laluan untuk simpan gambar profil staf
        $table->timestamps(); // Kolum created_at dan updated_at
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_details');
    }
};
