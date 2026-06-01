<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('forexoptions', function (Blueprint $table) {
            $table->index('Symbol', 'idx_forexoptions_symbol');
            $table->index(['Isactive', 'instrument', 'Symbol'], 'idx_forex_isactive_inst_sym');
            $table->index('ExpiryDate', 'idx_forexoptions_expiry_date');
        });

        Schema::table('clientsubscription', function (Blueprint $table) {
            $table->index(['UserId', 'Symbol'], 'idx_clientsub_user_symbol');
            $table->index('Symbol', 'idx_clientsub_symbol');
        });

        Schema::table('userbannedsymbols', function (Blueprint $table) {
            $table->index(['brokerid', 'banned', 'symbol'], 'idx_banned_broker_banned_symbol');
        });

        Schema::table('marketbidmaster', function (Blueprint $table) {
            $table->index(['UserId', 'Isactive', 'Symbol'], 'idx_bid_user_active_sym');
            $table->index(['Symbol', 'Isactive'], 'idx_bid_sym_active');
        });

        Schema::table('adminlogin', function (Blueprint $table) {
            $table->index('parent_id', 'idx_admin_parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forexoptions', function (Blueprint $table) {
            $table->dropIndex('idx_forexoptions_symbol');
            $table->dropIndex('idx_forex_isactive_inst_sym');
            $table->dropIndex('idx_forexoptions_expiry_date');
        });

        Schema::table('clientsubscription', function (Blueprint $table) {
            $table->dropIndex('idx_clientsub_user_symbol');
            $table->dropIndex('idx_clientsub_symbol');
        });

        Schema::table('userbannedsymbols', function (Blueprint $table) {
            $table->dropIndex('idx_banned_broker_banned_symbol');
        });

        Schema::table('marketbidmaster', function (Blueprint $table) {
            $table->dropIndex('idx_bid_user_active_sym');
            $table->dropIndex('idx_bid_sym_active');
        });

        Schema::table('adminlogin', function (Blueprint $table) {
            $table->dropIndex('idx_admin_parent_id');
        });
    }
};
