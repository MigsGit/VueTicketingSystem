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
        Schema::create('resolution_procedure_lists', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->foreignId('resolution_procedure_title_id')->constrained(); //foreign key for title
            $table->string('procedure_list');
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
        Schema::dropIfExists('resolution_procedure_lists');
    }
};
