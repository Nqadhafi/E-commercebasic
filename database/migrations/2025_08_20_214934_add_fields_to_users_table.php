<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(){
    Schema::table('users', function (Blueprint $t){
        $t->string('phone')->nullable();
        $t->string('role')->default('customer'); // admin|customer
        $t->boolean('wa_opt_in')->default(true);
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down(){
    Schema::table('users', function (Blueprint $t){
        $t->dropColumn(['phone','role','wa_opt_in']);
    });
}
}
