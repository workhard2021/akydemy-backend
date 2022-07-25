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
            $table->string('movie_module_url',100)->nullable();
            $table->boolean('pdf_resource',100)->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('default_resource')->default(false);
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
