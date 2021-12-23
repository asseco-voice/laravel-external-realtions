<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAcknowledgedToRemoteRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remote_relations', function (Blueprint $table) {
            $table->dateTime('acknowledged')->after('service')->nullable();
        });

        // Assume current data is acknowledged
        DB::table('remote_relations')->update([
            'acknowledged' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remote_relations', function (Blueprint $table) {
            $table->dropColumn('acknowledged');
        });
    }
}
