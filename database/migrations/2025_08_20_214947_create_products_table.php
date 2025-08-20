<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(){
    Schema::create('products', function (Blueprint $t){
        $t->id();
        $t->string('name');
        $t->string('sku')->unique();
        $t->integer('price');
        $t->integer('stock');
        $t->integer('weight_gram')->default(0);
        $t->string('thumbnail')->nullable();
        $t->boolean('is_active')->default(true);
        $t->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down(){ Schema::dropIfExists('products'); }
}
