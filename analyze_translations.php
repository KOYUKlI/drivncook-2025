<?php

/**
 * Script d'analyse des traductions UI
 *
 * Ce script compare les clés de traduction disponibles dans les fichiers
 * /lang/en/ui.php et /lang/fr/ui.php avec les clés utilisées dans le projet
 * via __('ui.*') pour identifier les traductions manquantes.
 */
class TranslationAnalyzer
{
    private $basePath;

    private $enKeys = [];

    private $frKeys = [];

    private $usedKeys = [];

    public function __construct($basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * Extraire toutes les clés d'un fichier de traduction de manière récursive
     */
    private function extractKeysFromArray($array, $prefix = '')
    {
        $keys = [];

        foreach ($array as $key => $value) {
            $fullKey = $prefix ? $prefix.'.'.$key : $key;

            if (is_array($value)) {
                // Récursion pour les sous-tableaux
                $keys = array_merge($keys, $this->extractKeysFromArray($value, $fullKey));
            } else {
                // C'est une clé finale
                $keys[] = $fullKey;
            }
        }

        return $keys;
    }

    /**
     * Charger les clés des fichiers de traduction
     */
    public function loadTranslationKeys()
    {
        $enFile = $this->basePath.'/lang/en/ui.php';
        $frFile = $this->basePath.'/lang/fr/ui.php';

        if (file_exists($enFile)) {
            $enData = include $enFile;
            $this->enKeys = $this->extractKeysFromArray($enData);
            echo '✅ Fichier EN chargé: '.count($this->enKeys)." clés trouvées\n";
        } else {
            echo "❌ Fichier EN non trouvé: $enFile\n";
        }

        if (file_exists($frFile)) {
            $frData = include $frFile;
            $this->frKeys = $this->extractKeysFromArray($frData);
            echo '✅ Fichier FR chargé: '.count($this->frKeys)." clés trouvées\n";
        } else {
            echo "❌ Fichier FR non trouvé: $frFile\n";
        }
    }

    /**
     * Scanner les fichiers du projet pour trouver les utilisations de __('ui.*')
     */
    public function scanProjectForUsedKeys()
    {
        $patterns = [
            "/\b__\(\s*['\"]ui\.([^'\"]+)['\"]\s*\)/",
            "/\btrans\(\s*['\"]ui\.([^'\"]+)['\"]\s*\)/",
            "/\@lang\(\s*['\"]ui\.([^'\"]+)['\"]\s*\)/",
        ];

        $directories = [
            $this->basePath.'/app',
            $this->basePath.'/resources/views',
            $this->basePath.'/routes',
        ];

        $extensions = ['php', 'blade.php'];

        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $this->scanDirectory($dir, $patterns, $extensions);
            }
        }

        $this->usedKeys = array_unique($this->usedKeys);
        sort($this->usedKeys);

        echo '✅ Scan terminé: '.count($this->usedKeys)." clés 'ui.*' utilisées trouvées\n";
    }

    /**
     * Scanner un répertoire récursivement
     */
    private function scanDirectory($dir, $patterns, $extensions)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = $file->getExtension();
                $filename = $file->getFilename();

                // Vérifier les extensions
                $matchesExtension = false;
                foreach ($extensions as $ext) {
                    if ($extension === $ext || ($ext === 'blade.php' && str_ends_with($filename, '.blade.php'))) {
                        $matchesExtension = true;
                        break;
                    }
                }

                if ($matchesExtension) {
                    $this->scanFile($file->getPathname(), $patterns);
                }
            }
        }
    }

    /**
     * Scanner un fichier spécifique
     */
    private function scanFile($filePath, $patterns)
    {
        $content = file_get_contents($filePath);

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $this->usedKeys[] = $match[1]; // La clé capturée
                }
            }
        }
    }

    /**
     * Analyser et afficher les résultats
     */
    public function analyze()
    {
        echo "\n".str_repeat('=', 80)."\n";
        echo "🔍 ANALYSE DES TRADUCTIONS UI\n";
        echo str_repeat('=', 80)."\n";

        // Clés manquantes en français
        $missingInFr = array_diff($this->enKeys, $this->frKeys);
        if (! empty($missingInFr)) {
            echo "\n❌ CLÉS MANQUANTES EN FRANÇAIS (".count($missingInFr)."):\n";
            echo str_repeat('-', 50)."\n";
            foreach ($missingInFr as $key) {
                echo "  - ui.$key\n";
            }
        } else {
            echo "\n✅ Toutes les clés EN sont présentes en FR\n";
        }

        // Clés manquantes en anglais
        $missingInEn = array_diff($this->frKeys, $this->enKeys);
        if (! empty($missingInEn)) {
            echo "\n❌ CLÉS MANQUANTES EN ANGLAIS (".count($missingInEn)."):\n";
            echo str_repeat('-', 50)."\n";
            foreach ($missingInEn as $key) {
                echo "  - ui.$key\n";
            }
        } else {
            echo "\n✅ Toutes les clés FR sont présentes en EN\n";
        }

        // Clés utilisées mais non définies
        $allDefinedKeys = array_unique(array_merge($this->enKeys, $this->frKeys));
        $undefinedKeys = array_diff($this->usedKeys, $allDefinedKeys);
        if (! empty($undefinedKeys)) {
            echo "\n❌ CLÉS UTILISÉES MAIS NON DÉFINIES (".count($undefinedKeys)."):\n";
            echo str_repeat('-', 50)."\n";
            foreach ($undefinedKeys as $key) {
                echo "  - ui.$key\n";
            }
        } else {
            echo "\n✅ Toutes les clés utilisées sont définies\n";
        }

        // Clés définies mais non utilisées
        $unusedKeys = array_diff($allDefinedKeys, $this->usedKeys);
        if (! empty($unusedKeys)) {
            echo "\n⚠️  CLÉS DÉFINIES MAIS NON UTILISÉES (".count($unusedKeys)."):\n";
            echo str_repeat('-', 50)."\n";
            foreach ($unusedKeys as $key) {
                echo "  - ui.$key\n";
            }
        } else {
            echo "\n✅ Toutes les clés définies sont utilisées\n";
        }

        // Résumé
        echo "\n".str_repeat('=', 80)."\n";
        echo "📊 RÉSUMÉ:\n";
        echo str_repeat('=', 80)."\n";
        echo '  • Clés EN définies: '.count($this->enKeys)."\n";
        echo '  • Clés FR définies: '.count($this->frKeys)."\n";
        echo '  • Clés utilisées dans le code: '.count($this->usedKeys)."\n";
        echo '  • Clés manquantes en FR: '.count($missingInFr)."\n";
        echo '  • Clés manquantes en EN: '.count($missingInEn)."\n";
        echo '  • Clés utilisées non définies: '.count($undefinedKeys)."\n";
        echo '  • Clés définies non utilisées: '.count($unusedKeys)."\n";

        // Suggestions d'actions
        if (! empty($missingInFr) || ! empty($missingInEn) || ! empty($undefinedKeys)) {
            echo "\n🔧 ACTIONS RECOMMANDÉES:\n";
            echo str_repeat('-', 50)."\n";

            if (! empty($missingInFr)) {
                echo "  1. Ajouter les traductions manquantes en français\n";
            }
            if (! empty($missingInEn)) {
                echo "  2. Ajouter les traductions manquantes en anglais\n";
            }
            if (! empty($undefinedKeys)) {
                echo "  3. Définir les clés utilisées mais manquantes\n";
            }
        }

        echo "\n";
    }

    /**
     * Générer les clés manquantes pour un ajout facile
     */
    public function generateMissingKeys()
    {
        $missingInFr = array_diff($this->enKeys, $this->frKeys);
        $allDefinedKeys = array_unique(array_merge($this->enKeys, $this->frKeys));
        $undefinedKeys = array_diff($this->usedKeys, $allDefinedKeys);

        if (! empty($missingInFr)) {
            echo "\n🔧 CLÉS À AJOUTER EN FRANÇAIS:\n";
            echo str_repeat('-', 50)."\n";
            foreach ($missingInFr as $key) {
                echo "    '$key' => 'TODO: Traduire',\n";
            }
        }

        if (! empty($undefinedKeys)) {
            echo "\n🔧 CLÉS À AJOUTER EN ANGLAIS ET FRANÇAIS:\n";
            echo str_repeat('-', 50)."\n";
            echo "EN:\n";
            foreach ($undefinedKeys as $key) {
                echo "    '$key' => 'TODO: Define',\n";
            }
            echo "FR:\n";
            foreach ($undefinedKeys as $key) {
                echo "    '$key' => 'TODO: Définir',\n";
            }
        }
    }

    /**
     * Exécuter l'analyse complète
     */
    public function run()
    {
        echo "🚀 Démarrage de l'analyse des traductions UI...\n\n";

        $this->loadTranslationKeys();
        echo "\n";
        $this->scanProjectForUsedKeys();
        $this->analyze();
        $this->generateMissingKeys();
    }
}

// Exécution du script
if (php_sapi_name() === 'cli') {
    $projectPath = dirname(__FILE__);
    $analyzer = new TranslationAnalyzer($projectPath);
    $analyzer->run();
} else {
    echo "Ce script doit être exécuté en ligne de commande.\n";
}
