<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test des traductions
echo "=== Test des traductions de validation ===\n";
echo "Locale actuelle : " . app()->getLocale() . "\n";

// Test de la traduction 'confirmed'
echo "Traduction 'confirmed' : " . __('validation.confirmed') . "\n";

// Test d'autres traductions
echo "Traduction 'required' : " . __('validation.required', ['attribute' => 'password']) . "\n";
echo "Traduction 'min' : " . __('validation.min.string', ['attribute' => 'password', 'min' => 8]) . "\n";

// Test des traductions auth
echo "\n=== Test des traductions auth ===\n";
echo "Failed : " . __('auth.failed') . "\n";
echo "Password : " . __('auth.password') . "\n";

echo "\n=== Test validation avec attributs ===\n";
$validator = app('validator')->make(
    ['password' => 'test', 'password_confirmation' => 'different'],
    ['password' => 'confirmed']
);

if ($validator->fails()) {
    foreach ($validator->errors()->all() as $error) {
        echo "Erreur: $error\n";
    }
}
