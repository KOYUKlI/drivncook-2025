<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            if (!Schema::hasColumn('trucks', 'ulid')) {
                $table->ulid('ulid')->nullable()->unique()->after('id');
            }
        });

        // Backfill existing rows using PHP-generated ULIDs
        $rows = DB::table('trucks')->select('id','ulid')->get();
        foreach ($rows as $row) {
            if (empty($row->ulid)) {
                DB::table('trucks')->where('id', $row->id)->update(['ulid' => (string) Str::ulid()]);
            }
        }
        // Keep nullable to avoid DBAL requirement; unique constraint already prevents duplicates
    }

    public function down(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            if (Schema::hasColumn('trucks', 'ulid')) {
                $table->dropColumn('ulid');
            }
        });
    }
};
