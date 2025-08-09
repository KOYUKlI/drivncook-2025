<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        $exists = DB::selectOne("SELECT COUNT(1) AS c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'franchises' AND index_name = 'uk_franchises_name'");
        if (!$exists || (int)$exists->c === 0) {
            Schema::table('franchises', function (Blueprint $table) {
                $table->unique('name', 'uk_franchises_name');
            });
        }
    }
    public function down(): void {
        Schema::table('franchises', function (Blueprint $table) {
            $table->dropUnique('uk_franchises_name');
        });
    }
};
