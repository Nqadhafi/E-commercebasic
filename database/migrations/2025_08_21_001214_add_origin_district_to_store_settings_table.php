<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginDistrictToStoreSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(){
    Schema::table('store_settings', function (Blueprint $t){
        $t->string('origin_district_id')->nullable()->after('origin_city_id');
    });
}
public function down(){
    Schema::table('store_settings', function (Blueprint $t){
        $t->dropColumn('origin_district_id');
    });
}
}
