<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void {
        foreach (['locations','truck_deployments'] as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'ulid')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->ulid('ulid')->nullable()->unique()->after('id');
                });
            }
            if (Schema::hasTable($table)) {
                $rows = DB::table($table)->select('id','ulid')->get();
                foreach ($rows as $row) {
                    if (empty($row->ulid)) {
                        DB::table($table)->where('id', $row->id)->update(['ulid' => (string) Str::ulid()]);
                    }
                }
            }
        }
    }
    public function down(): void {
        foreach (['locations','truck_deployments'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'ulid')) {
                Schema::table($table, function (Blueprint $table) { $table->dropColumn('ulid'); });
            }
        }
    }
};
