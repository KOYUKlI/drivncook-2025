<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('stock_order_items') && !Schema::hasColumn('stock_order_items', 'ulid')) {
            Schema::table('stock_order_items', function (Blueprint $table) {
                $table->ulid('ulid')->nullable()->unique()->after('id');
            });
            $rows = DB::table('stock_order_items')->select('id','ulid')->get();
            foreach ($rows as $row) {
                if (empty($row->ulid)) {
                    DB::table('stock_order_items')->where('id', $row->id)->update(['ulid' => (string) Str::ulid()]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stock_order_items') && Schema::hasColumn('stock_order_items', 'ulid')) {
            Schema::table('stock_order_items', function (Blueprint $table) {
                $table->dropColumn('ulid');
            });
        }
    }
};
