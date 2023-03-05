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
            $table->string('url_file',250)->nullable();
            $table->string('name_file',250)->nullable();
            $table->boolean('active')->default(false);
            $table->string('country',50)->nullable();
            $table->string('profession',250)->nullable();
            $table->string('description',255)->nullable();
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
