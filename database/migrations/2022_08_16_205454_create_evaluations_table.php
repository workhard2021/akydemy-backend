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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('title',200);
            $table->enum('type',eTypeEvaluation::getValues());
            $table->date('visibility_date_limit');
            $table->boolean('published')->nullable();
            $table->boolean('is_closed')->nullable();
            $table->string('url_file',200)->nullable();
            $table->string('name_file',200)->nullable();
            $table->foreignId('module_id')->constrained('modules')->onDelete('CASCADE');
            // $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('SET NULL');
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
        Schema::dropIfExists('evaluations');
    }
};
