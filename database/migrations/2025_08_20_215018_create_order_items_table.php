<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(){
    Schema::create('order_items', function (Blueprint $t){
        $t->id();
        $t->foreignId('order_id')->constrained()->onDelete('cascade');
        $t->foreignId('product_id')->constrained()->restrictOnDelete();
        $t->string('name_snapshot');
        $t->integer('price_snapshot');
        $t->integer('weight_gram_snapshot')->default(0);
        $t->integer('qty');
        $t->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down(){ Schema::dropIfExists('order_items'); }
}
