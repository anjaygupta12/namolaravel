<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivityColumnToAdminLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_logs', 'activity')) {
                $table->string('activity')->after('admin_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_logs', function (Blueprint $table) {
            if (Schema::hasColumn('admin_logs', 'activity')) {
                $table->dropColumn('activity');
            }
        });
    }
}
