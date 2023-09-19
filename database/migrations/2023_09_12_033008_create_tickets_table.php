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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no');
            $table->string('max_unique_no');
            $table->string('subject');
            $table->string('message');
            $table->string('status')->default(0)->comment = "0-unassigned, 1-assigned, 2-DNMR, 3-closed";
            $table->unsignedSmallInteger('assigned_to')->nullable();
            $table->unsignedSmallInteger('resolution_time')->nullable();
            $table->unsignedSmallInteger('created_by');
            $table->unsignedSmallInteger('updated_by')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
