<?php

use App\Enums\eStatus;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); 
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('tel',15)->nullable();
            $table->string('image_url',150)->nullable();
            $table->enum('status',eStatus::getValues())->default(eStatus::STUDIENT->value);
            $table->boolean('active')->default(false);
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('SET NULL');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropSoftDeletes();
        // });
    }
};
