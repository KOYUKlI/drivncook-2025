<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // trucks: unique license plate if present (skip if already exists)
        $exists = DB::selectOne("SELECT COUNT(1) AS c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'trucks' AND index_name = 'uk_trucks_plate'");
        if (!$exists || (int)$exists->c === 0) {
            Schema::table('trucks', function (Blueprint $table) {
                $table->unique('license_plate', 'uk_trucks_plate');
            });
        }

        // supplies: optional SKU uniqueness (add column if missing)
        if (!Schema::hasColumn('supplies', 'sku')) {
            Schema::table('supplies', function (Blueprint $table) {
                $table->string('sku')->nullable()->after('name');
            });
        }
        $exists = DB::selectOne("SELECT COUNT(1) AS c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'supplies' AND index_name = 'uk_supplies_sku'");
        if (!$exists || (int)$exists->c === 0) {
            Schema::table('supplies', function (Blueprint $table) {
                $table->unique('sku', 'uk_supplies_sku');
            });
        }

    // users: role/franchise constraint enforced at application level due to MySQL CHECK limitations with FK columns

        // customer_orders: add statuses, reference, check
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->enum('status', ['pending','confirmed','preparing','ready','completed','canceled'])->default('pending')->after('order_type');
            $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending')->after('status');
            $table->string('reference', 30)->nullable()->after('payment_status');
        });
        $exists = DB::selectOne("SELECT COUNT(1) AS c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'customer_orders' AND index_name = 'uk_orders_ref'");
        if (!$exists || (int)$exists->c === 0) {
            Schema::table('customer_orders', function (Blueprint $table) {
                $table->unique('reference', 'uk_orders_ref');
            });
        }
        DB::statement("ALTER TABLE customer_orders ADD CONSTRAINT chk_orders_reservation CHECK ( (order_type='reservation' AND pickup_at IS NOT NULL) OR (order_type<>'reservation') )");

        // truck_deployments: date range check (index already exists from creation)
        DB::statement("ALTER TABLE truck_deployments ADD CONSTRAINT chk_td_range CHECK (ends_at IS NULL OR ends_at > starts_at)");

        // commissions: add generated column amount_due
        if (!Schema::hasColumn('commissions', 'amount_due')) {
            Schema::table('commissions', function (Blueprint $table) {
                $table->decimal('amount_due', 12, 2)->nullable()->after('rate');
            });
            DB::statement("ALTER TABLE commissions MODIFY amount_due DECIMAL(12,2) AS (ROUND(turnover * rate / 100, 2)) STORED");
        }

        // performance indexes (some may already exist)
        $exists = DB::selectOne("SELECT COUNT(1) AS c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'order_items' AND index_name = 'ix_items_order'");
        if (!$exists || (int)$exists->c === 0) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->index('customer_order_id', 'ix_items_order');
            });
        }
        $exists = DB::selectOne("SELECT COUNT(1) AS c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'stock_orders' AND index_name = 'ix_so_truck_created'");
        if (!$exists || (int)$exists->c === 0) {
            Schema::table('stock_orders', function (Blueprint $table) {
                $table->index(['truck_id','created_at'], 'ix_so_truck_created');
            });
        }
        // inventory index is covered by unique key; ensure separate index if needed
        $exists = DB::selectOne("SELECT COUNT(1) AS c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'inventory' AND index_name = 'ix_inv_wh_supply'");
        if (!$exists || (int)$exists->c === 0) {
            Schema::table('inventory', function (Blueprint $table) {
                $table->index(['warehouse_id','supply_id'], 'ix_inv_wh_supply');
            });
        }
    }

    public function down(): void {
        // drop added indexes & constraints cautiously
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropUnique('uk_trucks_plate');
        });
        if (Schema::hasColumn('supplies', 'sku')) {
            Schema::table('supplies', function (Blueprint $table) {
                $table->dropUnique('uk_supplies_sku');
                $table->dropColumn('sku');
            });
        }
    // nothing to drop for users check (not created)

        Schema::table('customer_orders', function (Blueprint $table) {
            $table->dropUnique('uk_orders_ref');
            $table->dropColumn(['reference','payment_status','status']);
        });
        DB::statement('ALTER TABLE customer_orders DROP CHECK chk_orders_reservation');

        DB::statement('ALTER TABLE truck_deployments DROP CHECK chk_td_range');

        if (Schema::hasColumn('commissions', 'amount_due')) {
            Schema::table('commissions', function (Blueprint $table) {
                $table->dropColumn('amount_due');
            });
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('ix_items_order');
        });
        Schema::table('stock_orders', function (Blueprint $table) {
            $table->dropIndex('ix_so_truck_created');
        });
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropIndex('ix_inv_wh_supply');
        });
    }
};
