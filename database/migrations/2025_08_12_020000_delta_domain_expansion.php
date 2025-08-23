<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','role')) {
                $table->enum('role',['admin','franchise','customer'])->default('customer')->after('password');
            }
            if (!Schema::hasColumn('users','franchise_id')) {
                $table->foreignId('franchise_id')->nullable()->after('role')->constrained('franchises')->nullOnDelete();
            }
            if (!Schema::hasColumn('users','preferred_language')) {
                $table->string('preferred_language',5)->nullable()->after('franchise_id');
            }
            // Mission 2 removed: newsletter_opt_in skipped
        });

        Schema::table('trucks', function (Blueprint $table) {
            if (!Schema::hasColumn('trucks','status')) {
                $table->enum('status',['active','maintenance','inactive'])->default('active')->after('license_plate');
            }
            if (!Schema::hasColumn('trucks','deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('supplies','unit')) {
                $table->string('unit',10)->default('pc')->after('name');
            }
            if (!Schema::hasColumn('supplies','cost')) {
                $table->decimal('cost',12,2)->default(0)->after('unit');
            }
        });

    // Mission 2 removed: loyalty_cards adjustments skipped

    // Mission 2 removed: newsletters tables creation skipped

        Schema::table('customer_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_orders','customer_id')) {
                $table->foreignId('customer_id')->nullable()->after('truck_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('customer_orders','order_type')) {
                $table->enum('order_type',['online','on_site','reservation'])->default('online')->after('customer_id');
            }
            if (!Schema::hasColumn('customer_orders','pickup_at')) {
                $table->timestamp('pickup_at')->nullable()->after('ordered_at');
            }
            if (!Schema::hasColumn('customer_orders','location_id')) {
                $table->foreignId('location_id')->nullable()->after('pickup_at')->constrained('locations')->nullOnDelete();
            }
        });
        try {
            DB::statement("ALTER TABLE customer_orders ADD CONSTRAINT chk_online_ref CHECK ( (order_type='online' AND reference IS NOT NULL) OR order_type <> 'online' )");
        } catch (Throwable $e) {}
    }

    public function down(): void {
    // Mission 2 removed: nothing to drop here
    }
};
