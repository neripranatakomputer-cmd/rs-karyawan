<?php
// database/migrations/xxxx_create_karyawans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            
            // Data Pribadi
            $table->string('foto_profil')->nullable();
            $table->string('nip')->unique();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('nama_gelar')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            
            // Data Kontak
            $table->string('no_hp', 15);
            $table->string('email')->unique();
            $table->text('alamat');
            
            // Data Pendidikan
            $table->string('pendidikan_terakhir');
            $table->year('tahun_lulus');
            $table->string('ijazah')->nullable();
            
            // Data Kepegawaian
            $table->string('jabatan');
            $table->string('unit');
            $table->string('sip')->nullable();
            $table->string('str')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};