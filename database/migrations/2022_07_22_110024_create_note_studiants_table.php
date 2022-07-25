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
        Schema::create('note_studiants', function (Blueprint $table) {
            $table->id();
            $table->double('note',4,2);
            $table->double('note_teacher',4,2)->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE');
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('SET NULL');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('SET NULL');
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
        Schema::dropIfExists('note_studiants');
    }
};
