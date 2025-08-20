<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(){
    Schema::create('notification_logs', function (Blueprint $t){
        $t->id();
        $t->string('channel'); // whatsapp
        $t->string('event');   // ORDER_PLACED, ...
        $t->string('target');  // nomor WA
        $t->json('payload')->nullable();
        $t->integer('status_code')->nullable();
        $t->boolean('success')->default(false);
        $t->text('response_body')->nullable();
        $t->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
        $t->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down(){ Schema::dropIfExists('notification_logs'); }
}
