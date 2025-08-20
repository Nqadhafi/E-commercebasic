<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(){
    Schema::create('orders', function (Blueprint $t){
        $t->id();
        $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

        $t->string('ship_name');
        $t->string('ship_phone');
        $t->text('ship_address');
        $t->string('ship_province');
        $t->string('ship_city');
        $t->string('ship_postal');
        $t->integer('destination_city_id');

        $t->string('courier');   // jne
        $t->string('service');   // REG
        $t->string('etd')->nullable();
        $t->integer('shipping_cost');

        $t->integer('subtotal');
        $t->integer('discount')->default(0);
        $t->integer('grandtotal');

        $t->string('resi')->nullable();
        $t->string('status')->default('pending'); // pending|paid|processing|shipped|completed|canceled
        $t->timestamp('ordered_at')->useCurrent();

        $t->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down(){ Schema::dropIfExists('orders'); }
}
