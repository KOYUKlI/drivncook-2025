<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Diagnostic complet des clés ui. manquantes\n";
echo "==========================================\n\n";

// Fonction pour tester le rendu d'une vue avec un utilisateur fictif
function testViewWithAuth($viewName, $userRole = null)
{
    try {
        if ($userRole) {
            // Créer un utilisateur temporaire pour les vues authentifiées
            $user = new \App\Models\User([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
            ]);
            $user->id = 1;

            // Simuler l'authentification
            \Illuminate\Support\Facades\Auth::setUser($user);

            // Assigner le rôle si nécessaire
            if ($userRole === 'admin') {
                $user->assignRole('admin');
            } elseif ($userRole === 'franchisee') {
                $user->assignRole('franchisee');
            }
        }

        $content = view($viewName)->render();

        // Chercher les clés ui. non traduites
        preg_match_all('/ui\.[a-zA-Z_]+/', $content, $matches);

        if ($userRole) {
            \Illuminate\Support\Facades\Auth::logout();
        }

        return array_unique($matches[0]);
    } catch (Exception $e) {
        return ['ERROR: '.$e->getMessage()];
    }
}

// Tester différents types de vues
$viewsToTest = [
    // Vues publiques
    'public.home' => null,
    'public.franchise-info' => null,

    // Vues avec sidebar (nécessitent authentification)
    'layouts.partials.sidebar' => 'admin',
];

$allFoundKeys = [];

foreach ($viewsToTest as $viewName => $userRole) {
    echo "Testing $viewName".($userRole ? " (as $userRole)" : '').":\n";

    $keys = testViewWithAuth($viewName, $userRole);

    if (empty($keys)) {
        echo "  ✅ Aucune clé ui. non traduite\n";
    } else {
        echo '  ⚠️  Clés trouvées: '.implode(', ', $keys)."\n";
        $allFoundKeys = array_merge($allFoundKeys, $keys);
    }
    echo "\n";
}

// Vérifier aussi directement dans les fichiers
echo "Vérification directe dans les fichiers de vues:\n";
echo "----------------------------------------------\n";

$command = 'find resources/views -name "*.blade.php" -exec grep -l "ui\." {} \;';
$files = shell_exec($command);
$viewFiles = array_filter(explode("\n", trim($files)));

foreach ($viewFiles as $file) {
    $content = file_get_contents($file);
    preg_match_all('/__\([\'"]ui\.([a-zA-Z_]+)[\'"]\)/', $content, $matches);

    if (! empty($matches[1])) {
        $shortFile = str_replace('resources/views/', '', $file);
        echo "$shortFile:\n";
        foreach (array_unique($matches[1]) as $key) {
            // Vérifier si la clé existe dans le fichier de traduction
            $frContent = file_get_contents('lang/fr/ui.php');
            if (strpos($frContent, "'$key'") === false) {
                echo "  ❌ MANQUANTE: ui.$key\n";
                $allFoundKeys[] = "ui.$key";
            } else {
                echo "  ✅ OK: ui.$key\n";
            }
        }
        echo "\n";
    }
}

$uniqueKeys = array_unique($allFoundKeys);
if (! empty($uniqueKeys)) {
    echo "RÉSUMÉ - Clés manquantes trouvées:\n";
    echo "================================\n";
    foreach ($uniqueKeys as $key) {
        if (! str_starts_with($key, 'ERROR:')) {
            echo "- $key\n";
        }
    }
} else {
    echo "🎉 PARFAIT! Aucune clé ui. manquante trouvée!\n";
}
