<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Recherche EXHAUSTIVE des clés ui. manquantes\n";
echo "=============================================\n\n";

// Fonction pour extraire toutes les clés ui. d'un fichier
function extractUiKeys($filePath) {
    $content = file_get_contents($filePath);
    preg_match_all('/__\([\'"]ui\.([a-zA-Z_]+)[\'"]\)/', $content, $matches);
    return array_unique($matches[1]);
}

// Fonction pour vérifier si une clé existe dans le fichier de traduction
function keyExists($key) {
    $frContent = file_get_contents('lang/fr/ui.php');
    return strpos($frContent, "'$key'") !== false;
}

// Parcourir tous les fichiers blade
$command = 'find resources/views -name "*.blade.php"';
$files = shell_exec($command);
$viewFiles = array_filter(explode("\n", trim($files)));

$allMissingKeys = [];

echo "Vérification fichier par fichier:\n";
echo "---------------------------------\n";

foreach ($viewFiles as $file) {
    $shortFile = str_replace('resources/views/', '', $file);
    $keys = extractUiKeys($file);
    
    $missingKeys = [];
    foreach ($keys as $key) {
        if (!keyExists($key)) {
            $missingKeys[] = $key;
            $allMissingKeys[] = $key;
        }
    }
    
    if (!empty($missingKeys)) {
        echo "❌ $shortFile:\n";
        foreach ($missingKeys as $key) {
            echo "   - ui.$key\n";
        }
        echo "\n";
    }
}

// Chercher aussi dans les composants
$command = 'find resources/views/components -name "*.blade.php" 2>/dev/null';
$componentFiles = shell_exec($command);
if ($componentFiles) {
    $componentFiles = array_filter(explode("\n", trim($componentFiles)));
    
    foreach ($componentFiles as $file) {
        $shortFile = str_replace('resources/views/', '', $file);
        $keys = extractUiKeys($file);
        
        $missingKeys = [];
        foreach ($keys as $key) {
            if (!keyExists($key)) {
                $missingKeys[] = $key;
                $allMissingKeys[] = $key;
            }
        }
        
        if (!empty($missingKeys)) {
            echo "❌ $shortFile (component):\n";
            foreach ($missingKeys as $key) {
                echo "   - ui.$key\n";
            }
            echo "\n";
        }
    }
}

$uniqueMissingKeys = array_unique($allMissingKeys);

if (empty($uniqueMissingKeys)) {
    echo "🎉 Aucune clé ui. manquante trouvée!\n";
} else {
    echo "RÉSUMÉ - TOUTES LES CLÉS MANQUANTES:\n";
    echo "====================================\n";
    foreach ($uniqueMissingKeys as $key) {
        echo "- ui.$key\n";
    }
    echo "\nTotal: " . count($uniqueMissingKeys) . " clés manquantes\n";
}
