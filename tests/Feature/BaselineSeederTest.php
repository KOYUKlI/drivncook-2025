<?php

use Illuminate\Support\Facades\Artisan;
use App\Models\{User, Franchise, Supply};

it('baseline seeder is idempotent', function () {
    putenv('SEED_PROFILE=minimal');
    Artisan::call('db:seed', ['--class' => 'Database\Seeders\BaselineSeeder']);
    $u1 = User::count();
    $f1 = Franchise::count();
    $s1 = Supply::count();
    Artisan::call('db:seed', ['--class' => 'Database\Seeders\BaselineSeeder']);
    expect(User::count())->toBe($u1);
    expect(Franchise::count())->toBe($f1);
    expect(Supply::count())->toBe($s1);
});
