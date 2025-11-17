<?php
// database/migrations/xxxx_create_custom_fields_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_name');
            $table->string('field_label');
            $table->enum('field_type', ['text', 'textarea', 'number', 'date', 'file', 'select']);
            $table->text('field_options')->nullable(); // untuk select options
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};