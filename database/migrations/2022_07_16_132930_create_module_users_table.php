<?php

use App\Enums\eStatusAttestation;
use App\Enums\eTypeCertificate;
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
        Schema::create('module_users', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->double('somme',8,2);
            $table->enum('type',[eTypeCertificate::getValues()])->default(eTypeCertificate::NONE->value);
            $table->enum('status_attestation',eStatusAttestation::getValues())->default(eStatusAttestation::NONE->value);
            $table->boolean('is_valide')->default(false);
            $table->text('description')->nullable();
            $table->string('url_attestation',200)->nullable();
            $table->string('name_attestation',200)->nullable();
            $table->foreignId('module_id')->constrained('modules')->onDelete('CASCADE');
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE');
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
        Schema::dropIfExists('module_users');
    }
};
