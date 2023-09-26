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
        Schema::create('ticket_close_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained(); //foreign key for ticket
            $table->foreignId('resolution_procedure_title_id')->constrained(); //foreign key for resolution_procedure_title
            $table->string('initial_assessement_summary')->nullable();
            $table->string('root_cause')->nullable();
            $table->string('reference_link')->nullable();
            $table->dateTime('date_time_closed')->nullable();
            $table->dateTime('date_time_resolved')->nullable();
            $table->smallInteger('is_close')->nullable()->comment('1-Yes|2-No');
            $table->smallInteger('conformance_mode')->nullable()->comment('1-Verbal|2-Email');;
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
        Schema::dropIfExists('ticket_close_details');
    }
};
