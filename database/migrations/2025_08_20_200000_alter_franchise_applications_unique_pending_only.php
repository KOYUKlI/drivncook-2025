<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		// Ensure unique constraint only applies to pending applications per email
		// Best-effort drop of previous unique on email if it exists in MySQL
		$connection = Schema::getConnection();
		$database = $connection->getDatabaseName();
		$hasIndex = $connection->selectOne('SELECT COUNT(1) AS cnt FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?', [
			$database, 'franchise_applications', 'franchise_applications_email_unique',
		]);
		if (($hasIndex->cnt ?? 0) > 0) {
			Schema::table('franchise_applications', function (Blueprint $table) {
				$table->dropUnique('franchise_applications_email_unique');
			});
		}

		Schema::table('franchise_applications', function (Blueprint $table) {
			// Create a composite unique on (email, status) so only one pending per email
			$table->unique(['email', 'status'], 'franchise_apps_email_status_unique');
		});
	}

	public function down(): void
	{
		Schema::table('franchise_applications', function (Blueprint $table) {
			try {
				$table->dropUnique('franchise_apps_email_status_unique');
			} catch (Throwable $e) {
				// ignore
			}
			// Optionally restore single unique on email
			// $table->unique('email');
		});
	}
};

