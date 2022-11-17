<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ressources_modules', function (Blueprint $table) {
            $table->id();
            $table->string('title',255)->nullable();
            $table->string('url_movie',255)->nullable();
            $table->string('url_movie_remove',255)->nullable();
            $table->string('name_movie',255)->nullable();
            $table->string('url_pdf',255)->nullable();
            $table->string('name_pdf',255)->nullable();
            $table->boolean('is_public')->nullable();
            $table->boolean('is_default')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('module_id')->constrained('modules')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ressources_modules');
    }
};
