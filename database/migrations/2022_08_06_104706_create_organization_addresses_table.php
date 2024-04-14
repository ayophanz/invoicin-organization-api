<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('organization_uuid');
            $table->unsignedBigInteger('organization_address_type_id');
            $table->text('address');
            $table->string('city');
            $table->string('zipcode');
            $table->string('country');
            $table->timestamps();
        });

        Schema::table('organization_addresses', function (Blueprint $table) {
            $table->foreign('organization_address_type_id')->references('id')->on('organization_address_types')->onDelete('cascade');
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
        Schema::dropIfExists('organization_addresses');
    }
}
