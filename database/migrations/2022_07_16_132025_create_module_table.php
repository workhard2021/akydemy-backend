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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->boolean('is_active')->default(false);
            $table->double('price',8,2);
            $table->double('promo_price',8,2)->nullable();
            $table->string('url_file',200)->nullable();
            $table->string('name_file',200)->nullable();
            $table->integer('nbr_month')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('SET NULL');
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('CASCADE');
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
        Schema::dropIfExists('modules');
    }
};
