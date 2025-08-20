<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(){
    Schema::create('customer_addresses', function (Blueprint $t){
        $t->id();
        $t->foreignId('user_id')->constrained()->onDelete('cascade');
        $t->string('label')->nullable();
        $t->string('receiver_name');
        $t->string('phone');
        $t->text('address_line');
        $t->string('province');
        $t->string('city');
        $t->string('postal_code');
        $t->integer('rajaongkir_city_id');
        $t->boolean('is_default')->default(true);
        $t->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down(){ Schema::dropIfExists('customer_addresses'); }
}
