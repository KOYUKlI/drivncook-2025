<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        $driver = DB::getDriverName();

        // stock_orders.status enum & default
        if (Schema::hasTable('stock_orders')) {
            try { DB::statement("ALTER TABLE stock_orders MODIFY status ENUM('pending','approved','completed','canceled') NOT NULL DEFAULT 'pending'"); } catch (Throwable $e) {}
        }

        // XOR warehouse / supplier (CHECK)
        if ($driver === 'mysql' && Schema::hasTable('stock_orders')) {
            try { DB::statement("ALTER TABLE stock_orders ADD CONSTRAINT chk_so_xor_source CHECK ( ((warehouse_id IS NOT NULL)+(supplier_id IS NOT NULL)) = 1 )"); } catch (Throwable $e) {}
        }

        // stock_order_items quantity >=1 (replace >=0)
        if ($driver === 'mysql' && Schema::hasTable('stock_order_items')) {
            try { DB::statement('ALTER TABLE stock_order_items DROP CHECK chk_stock_order_items_qty'); } catch (Throwable $e) {}
            try { DB::statement('ALTER TABLE stock_order_items ADD CONSTRAINT chk_stock_order_items_qty_pos CHECK (quantity >= 1)'); } catch (Throwable $e) {}
        }

    // Mission 2 events removed; skip event_registrations constraints

        // truck_deployments CHECK ensure present (one already earlier maybe)
        if ($driver === 'mysql' && Schema::hasTable('truck_deployments')) {
            try { DB::statement("ALTER TABLE truck_deployments ADD CONSTRAINT chk_td_range2 CHECK (ends_at IS NULL OR ends_at > starts_at)"); } catch (Throwable $e) {}
        }

        // sessions.user_id FK nullable -> users.id
        if (Schema::hasTable('sessions')) {
            try { DB::statement('ALTER TABLE sessions ADD CONSTRAINT fk_sessions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL'); } catch (Throwable $e) {}
        }

        // suppliers.siret unique
        if ($driver === 'mysql' && Schema::hasTable('suppliers') && Schema::hasColumn('suppliers','siret')) {
            try { DB::statement('ALTER TABLE suppliers ADD CONSTRAINT uk_suppliers_siret UNIQUE (siret)'); } catch (Throwable $e) {}
        }

        // supplies.sku unique
        if ($driver === 'mysql' && Schema::hasTable('supplies') && Schema::hasColumn('supplies','sku')) {
            try { DB::statement('ALTER TABLE supplies ADD CONSTRAINT uk_supplies_sku UNIQUE (sku)'); } catch (Throwable $e) {}
        }

        // Triggers: order total recompute (inline subquery to avoid VIEW + SUPER privilege issues)
        if ($driver === 'mysql' && Schema::hasTable('order_items') && Schema::hasTable('customer_orders')) {
            foreach (['order_items_ai' => 'AFTER INSERT','order_items_au' => 'AFTER UPDATE','order_items_ad' => 'AFTER DELETE'] as $name=>$timing) {
                try { DB::statement("DROP TRIGGER IF EXISTS $name"); } catch (Throwable $e) {}
                $ref = $timing === 'AFTER DELETE' ? 'OLD' : 'NEW';
                $body = "UPDATE customer_orders SET total_price = (SELECT SUM(quantity*price) FROM order_items WHERE customer_order_id = {$ref}.customer_order_id) WHERE id = {$ref}.customer_order_id";
                try { DB::statement("CREATE TRIGGER $name $timing ON order_items FOR EACH ROW BEGIN $body; END"); } catch (Throwable $e) {}
            }
        }

        // Trigger payment status propagation
                if ($driver === 'mysql' && Schema::hasTable('payments') && Schema::hasTable('customer_orders')) {
                        try { DB::statement('DROP TRIGGER IF EXISTS payments_au_status'); } catch (Throwable $e) {}
                        $paymentTrigger = <<<'SQL'
CREATE TRIGGER payments_au_status AFTER UPDATE ON payments FOR EACH ROW BEGIN
    IF NEW.status <> OLD.status THEN
        UPDATE customer_orders SET payment_status = CASE NEW.status
                WHEN 'captured' THEN 'paid'
                WHEN 'failed' THEN 'failed'
                WHEN 'refunded' THEN 'refunded'
                ELSE payment_status END
        WHERE id = NEW.customer_order_id;
    END IF;
END
SQL;
                        try { DB::statement($paymentTrigger); } catch (Throwable $e) { /* fallback: application layer observer already present */ }
                }

        // Purchase mix view (official_pct)
        if ($driver === 'mysql' && Schema::hasTable('stock_orders') && Schema::hasTable('stock_order_items')) {
            try { DB::statement('DROP VIEW IF EXISTS franchise_monthly_purchase_mix'); } catch (Throwable $e) {}
            $viewSql = <<<'SQL'
CREATE VIEW franchise_monthly_purchase_mix AS
SELECT f.id AS franchisee_id,
       YEAR(so.created_at) AS year,
       MONTH(so.created_at) AS month,
       SUM(CASE WHEN so.warehouse_id IS NOT NULL THEN soi.quantity * COALESCE(soi.unit_price,0) ELSE 0 END) AS internal_amount,
       SUM(CASE WHEN so.supplier_id IS NOT NULL THEN soi.quantity * COALESCE(soi.unit_price,0) ELSE 0 END) AS external_amount,
       CASE WHEN SUM(soi.quantity * COALESCE(soi.unit_price,0)) > 0
            THEN ROUND( (SUM(CASE WHEN so.warehouse_id IS NOT NULL THEN soi.quantity * COALESCE(soi.unit_price,0) ELSE 0 END) / SUM(soi.quantity * COALESCE(soi.unit_price,0))) * 100, 2)
            ELSE NULL END AS official_pct
FROM stock_orders so
JOIN trucks t ON t.id = so.truck_id
JOIN franchises f ON f.id = t.franchise_id
JOIN stock_order_items soi ON soi.stock_order_id = so.id
GROUP BY f.id, YEAR(so.created_at), MONTH(so.created_at);
SQL;
            DB::statement($viewSql);
        }
    }

    public function down(): void {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            try { DB::statement('DROP TRIGGER IF EXISTS payments_au_status'); } catch (Throwable $e) {}
            foreach (['order_items_ai','order_items_au','order_items_ad'] as $tr) { try { DB::statement("DROP TRIGGER IF EXISTS $tr"); } catch (Throwable $e) {} }
            try { DB::statement('DROP VIEW IF EXISTS franchise_monthly_purchase_mix'); } catch (Throwable $e) {}
        }
    }
};
