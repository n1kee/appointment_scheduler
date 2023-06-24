<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('surname');
            $table->string('phone');
            $table->integer('workdayStart');
            $table->integer('workdayEnd');
            $table->date('birthday')->nullable();
            $table->string('middlename')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->index(['surname', 'firstname', 'middlename'], 'doctors_fullname_index');
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('surname')->nullable();
            $table->string('snils');
            $table->date('birthday')->nullable();
            $table->string('homeAddress')->nullable();
            $table->timestamps();

            $table->index(['surname', 'firstname', 'middlename'], 'patients_fullname_index');
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('datetime');
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('appointments');
    }
}
