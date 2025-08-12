<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::getDriverName();

        // Constraints only for MySQL to avoid sqlite test issues
        if ($driver === 'mysql') {
            // Unique composite for event registrations (avoid double inscription)
            if (! $this->indexExists('event_registrations', 'uk_event_truck')) {
                DB::statement('ALTER TABLE event_registrations ADD CONSTRAINT uk_event_truck UNIQUE (event_id, truck_id)');
            }
            // Unique SIRET suppliers if column exists
            if ($this->columnExists('suppliers','siret') && ! $this->indexExists('suppliers','uk_suppliers_siret')) {
                DB::statement('ALTER TABLE suppliers ADD CONSTRAINT uk_suppliers_siret UNIQUE (siret)');
            }
            // Inventory lots lot_code mandatory + unique per inventory
            if ($this->columnExists('inventory_lots','lot_code')) {
                try { DB::statement('ALTER TABLE inventory_lots MODIFY lot_code VARCHAR(64) NOT NULL'); } catch (Throwable $e) { /* ignore if already */ }
                if (! $this->indexExists('inventory_lots','uk_invlot')) {
                    DB::statement('ALTER TABLE inventory_lots ADD CONSTRAINT uk_invlot UNIQUE (inventory_id, lot_code)');
                }
            }
            // Maintenance cost >= 0
            if ($this->tableExists('maintenance_records')) {
                try { DB::statement("ALTER TABLE maintenance_records ADD CONSTRAINT chk_maint_cost CHECK (cost IS NULL OR cost >= 0)"); } catch (Throwable $e) { /* maybe exists */ }
            }
            // Order items quantity >=1 (adjust existing >=0)
            if ($this->tableExists('order_items')) {
                try { DB::statement('ALTER TABLE order_items ADD CONSTRAINT chk_order_items_qty_pos CHECK (quantity >= 1)'); } catch (Throwable $e) { /* ignore duplicate */ }
            }
            // Customer orders completed => paid
            if ($this->tableExists('customer_orders')) {
                try { DB::statement("ALTER TABLE customer_orders ADD CONSTRAINT chk_completed_paid CHECK (status <> 'completed' OR payment_status = 'paid')"); } catch (Throwable $e) { /* ignore */ }
            }
            // Stock orders XOR source (one of warehouse or supplier)
            if ($this->tableExists('stock_orders')) {
                try { DB::statement("ALTER TABLE stock_orders ADD CONSTRAINT chk_so_source CHECK ( ((warehouse_id IS NOT NULL)+(supplier_id IS NOT NULL)) = 1 )"); } catch (Throwable $e) { /* ignore */ }
            }
            // Loyalty transactions points > 0
            if ($this->tableExists('loyalty_transactions')) {
                try { DB::statement('ALTER TABLE loyalty_transactions ADD CONSTRAINT chk_loy_points_pos CHECK (points > 0)'); } catch (Throwable $e) { /* ignore */ }
            }
        }

        // 80/20 purchase mix view (works for both drivers with some SQL differences minimal)
        // We use sum of (quantity * unit_price) from stock_order_items to compute internal vs external turnover
        $createView = <<<'SQL'
CREATE VIEW franchise_monthly_purchase_mix AS
SELECT f.id AS franchise_id,
       YEAR(so.created_at) AS year,
       MONTH(so.created_at) AS month,
       SUM(CASE WHEN so.warehouse_id IS NOT NULL THEN soi.quantity * soi.unit_price ELSE 0 END) AS internal_amount,
       SUM(CASE WHEN so.supplier_id IS NOT NULL THEN soi.quantity * soi.unit_price ELSE 0 END) AS external_amount,
       CASE WHEN SUM(soi.quantity * soi.unit_price) > 0
            THEN ROUND( (SUM(CASE WHEN so.warehouse_id IS NOT NULL THEN soi.quantity * soi.unit_price ELSE 0 END) / SUM(soi.quantity * soi.unit_price)) * 100, 2)
            ELSE NULL END AS internal_percentage
FROM stock_orders so
JOIN trucks t ON t.id = so.truck_id
JOIN franchises f ON f.id = t.franchise_id
JOIN stock_order_items soi ON soi.stock_order_id = so.id
GROUP BY f.id, YEAR(so.created_at), MONTH(so.created_at);
SQL;
        try { DB::statement('DROP VIEW IF EXISTS franchise_monthly_purchase_mix'); } catch (Throwable $e) { /* ignore */ }
        DB::statement($createView);
    }

    public function down(): void
    {
        try { DB::statement('DROP VIEW IF EXISTS franchise_monthly_purchase_mix'); } catch (Throwable $e) { /* ignore */ }
        // We do not attempt to drop constraints explicitly to avoid errors if they were absent; safe rollback optional.
    }

    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }
    private function columnExists(string $table, string $column): bool
    {
        return Schema::hasColumn($table, $column);
    }
    private function indexExists(string $table, string $index): bool
    {
        // MySQL only: query information_schema
        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                $res = DB::selectOne('SELECT COUNT(1) c FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?', [$table, $index]);
                return $res && $res->c > 0;
            }
        } catch (Throwable $e) { /* ignore */ }
        return false;
    }
};
