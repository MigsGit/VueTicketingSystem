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
            $table->string('ticket_no')->nullable();
            $table->string('max_unique_no')->nullable();
            $table->string('subject')->nullable();
            $table->string('message')->nullable();
            $table->string('status')->default(0)->comment = "0-unassigned, 1-assigned, 2-DNMR, 3-closed";
            $table->bigInteger('trt_id')->unsigned()->comment = "trt_id";
            $table->string('assigned_to')->nullable()->comment = "user_id";
            $table->unsignedSmallInteger('resolution_time')->nullable();
            $table->unsignedSmallInteger('created_by')->nullable();
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
