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
        Schema::create('technology_project', function (Blueprint $table) {
            $table->id();

            //creo la foreign per technology
            $table->unsignedBigInteger("technology_id");
            $table->foreign("technology_id")->references("id")->on("technologies");

            //creo la foreign per project
            $table->unsignedBigInteger("project_id");
            $table->foreign("project_id")->references("id")->on("projects");

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
        Schema::dropIfExists('technology_project');
    }
};