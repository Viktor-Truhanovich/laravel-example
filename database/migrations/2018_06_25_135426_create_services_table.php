<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_center_id')->unsigned();
            $table->foreign('business_center_id')->references('id')->on('business_centers')->onDelete('cascade');
            $table->integer('service_type_id')->unsigned();
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');
            $table->string('name');
            $table->decimal('price');
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
        Schema::table('services', function($table) {
            $table->dropForeign(['business_center_id']);
            $table->dropForeign(['service_type_id']);
        });
        Schema::dropIfExists('services');
    }
}
