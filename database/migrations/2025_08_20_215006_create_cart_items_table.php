<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(){
    Schema::create('cart_items', function (Blueprint $t){
        $t->id();
        $t->foreignId('cart_id')->constrained()->onDelete('cascade');
        $t->foreignId('product_id')->constrained()->restrictOnDelete();
        $t->integer('qty');
        $t->integer('price_at');
        $t->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down(){ Schema::dropIfExists('cart_items'); }
}
