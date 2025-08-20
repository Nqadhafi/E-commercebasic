<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(){
    Schema::create('store_settings', function (Blueprint $t){
        $t->id();
        $t->string('store_name')->nullable();
        $t->string('store_phone')->nullable();
        $t->text('store_address')->nullable();
        $t->string('rajaongkir_api_key')->nullable();
        $t->string('rajaongkir_account_type')->default('starter');
        $t->integer('origin_city_id')->nullable();
        $t->json('active_couriers')->nullable(); // ["jne","pos"]
        $t->integer('shipping_markup_flat')->default(0);
        $t->string('fonnte_token')->nullable();
        $t->string('fonnte_sender')->nullable();
        $t->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down(){ Schema::dropIfExists('store_settings'); }
}
