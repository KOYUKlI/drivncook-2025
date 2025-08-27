<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('franchisees', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->string('name');
            $t->string('email')->unique();
            $t->string('phone')->nullable();
            $t->string('billing_address')->nullable();
            $t->decimal('royalty_rate', 5, 4)->default(0.0400); // 4% [EXPLICITE]
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('warehouses', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->string('name');
            $t->string('city')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('stock_items', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->string('sku')->unique();
            $t->string('name');
            $t->string('unit', 16)->default('pcs');
            $t->unsignedBigInteger('price_cents')->default(0);
            $t->boolean('is_central')->default(true); // pour 80/20
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('trucks', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->string('plate')->unique();
            $t->enum('status', ['Draft', 'Active', 'InMaintenance', 'Retired'])->default('Draft');
            $t->date('service_start')->nullable();
            $t->foreignUlid('franchisee_id')->nullable()->constrained('franchisees')->nullOnDelete();
            $t->timestamps();
            $t->softDeletes();
            $t->index('status');
        });

        Schema::create('deployments', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('truck_id')->constrained('trucks')->cascadeOnDelete();
            $t->string('location');
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->timestamps();
        });

        Schema::create('maintenance_logs', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('truck_id')->constrained('trucks')->cascadeOnDelete();
            $t->enum('kind', ['Preventive', 'Corrective']);
            $t->text('description')->nullable();
            $t->timestamp('started_at')->useCurrent();
            $t->timestamp('closed_at')->nullable();
            $t->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $t->foreignUlid('franchisee_id')->constrained('franchisees')->cascadeOnDelete();
            $t->enum('status', ['Draft', 'Approved', 'Prepared', 'Shipped', 'Received', 'Cancelled'])->default('Draft');
            $t->decimal('corp_ratio_cached', 5, 2)->nullable(); // ratio 80/20
            $t->timestamps();
            $t->index('status');
        });

        Schema::create('purchase_lines', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $t->foreignUlid('stock_item_id')->constrained('stock_items')->restrictOnDelete();
            $t->unsignedInteger('qty');
            $t->unsignedBigInteger('unit_price_cents');
            $t->timestamps();
        });

        Schema::create('sales', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('franchisee_id')->constrained('franchisees')->cascadeOnDelete();
            $t->date('sale_date');
            $t->unsignedBigInteger('total_cents')->default(0);
            $t->timestamps();
            $t->index('sale_date');
        });

        Schema::create('sale_lines', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('sale_id')->constrained('sales')->cascadeOnDelete();
            $t->foreignUlid('stock_item_id')->nullable()->constrained('stock_items')->nullOnDelete();
            $t->unsignedInteger('qty');
            $t->unsignedBigInteger('unit_price_cents');
            $t->timestamps();
        });

        Schema::create('report_pdfs', function (Blueprint $t) {
            $t->ulid('id')->primary();
            $t->foreignUlid('franchisee_id')->constrained('franchisees')->cascadeOnDelete();
            $t->string('type', 40); // monthly_sales, maintenance...
            $t->unsignedSmallInteger('year');
            $t->unsignedTinyInteger('month')->nullable();
            $t->string('storage_path');
            $t->timestamp('generated_at');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_pdfs');
        Schema::dropIfExists('sale_lines');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('purchase_lines');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('maintenance_logs');
        Schema::dropIfExists('deployments');
        Schema::dropIfExists('trucks');
        Schema::dropIfExists('stock_items');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('franchisees');
    }
};
