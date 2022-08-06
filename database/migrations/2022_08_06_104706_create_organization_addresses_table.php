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
            $table->string('organization_id');
            $table->unsignedBigInteger('organization_address_type_id');
            $table->text('address');
            $table->unsignedBigInteger('country_id');
            $table->timestamps();
        });

        Schema::table('organization_addresses', function (Blueprint $table) {
            $table->foreign('organization_address_type_id')->references('id')->on('organization_address_types')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
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
