<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('organization_uuid');
            $table->unsignedBigInteger('address_type_id');
            $table->string('country');
            $table->string('state_province');
            $table->string('city');
            $table->string('zipcode');
            $table->text('address');
            $table->timestamps();
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign('address_type_id')->references('id')->on('address_types')->onDelete('cascade');
            $table->foreign('organization_uuid')->references('uuid')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
