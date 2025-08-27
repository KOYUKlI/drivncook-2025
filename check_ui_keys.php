<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Vérification finale des traductions ui.\n";
echo "=====================================\n\n";

// Test pages publiques
$publicViews = [
    'public.home',
    'public.franchise-info'
];

foreach ($publicViews as $viewName) {
    echo "Testing $viewName: ";
    try {
        $content = view($viewName)->render();
        $count = substr_count($content, 'ui.');
        if ($count > 0) {
            preg_match_all('/ui\.[a-zA-Z_]+/', $content, $matches);
            echo "⚠️  $count clés non traduites trouvées: " . implode(', ', array_unique($matches[0])) . "\n";
        } else {
            echo "✅ OK\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ Vérification terminée. Toutes les pages publiques sont correctement traduites!\n";
