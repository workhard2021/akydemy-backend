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
            $table->string('url_movie',50)->nullable();
            $table->string('name_movie',50)->nullable();
            $table->string('url_pdf',50)->nullable();
            $table->string('name_pdf',50)->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_default')->default(false);
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
