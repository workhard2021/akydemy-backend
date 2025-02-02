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
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->longText('description')->nullable()->comment('la description est dispensable');
            $table->boolean('is_active')->default(false);
            $table->string('url_file',250)->nullable();
            $table->string('name_file',250)->nullable();
            $table->string('name_file_dowload',250)->nullable();
            $table->string('url_file_dowload',250)->nullable();
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('CASCADE');
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
        Schema::dropIfExists('programmes');
    }
};
