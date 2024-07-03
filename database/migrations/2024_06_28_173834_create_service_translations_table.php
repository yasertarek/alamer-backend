<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('title')->unique();
            $table->text('subtitle');
            $table->text('content');
            $table->string('slug', 400)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_translations');
    }
}
