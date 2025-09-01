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
        Schema::create('navbar_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('navbar_id');
            $table->unsignedBigInteger('language_id'); // Language code or reference
            $table->foreign('navbar_id')->references('id')->on('navbars')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->unique(['navbar_id', 'language_id']);
            $table->string('title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navbar_translations');
    }
};
