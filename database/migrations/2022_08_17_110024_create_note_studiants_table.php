<?php

use App\Enums\eTypeEvaluation;
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
            $table->string('title',200)->comment('title module');
            $table->enum('type',eTypeEvaluation::getValues());
            $table->string('url_file',250)->nullable();
            $table->string('name_file',250)->nullable();
            $table->double('note',8,2)->nullable();
            $table->double('note_teacher',8,2)->nullable();
            $table->boolean('is_closed')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('SET NULL');
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('SET NULL');
            $table->foreignId('evaluation_id')->nullable()->constrained('evaluations')->onDelete('SET NULL');
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
