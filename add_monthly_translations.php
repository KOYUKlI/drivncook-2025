<?php

// Ce script ajoute les traductions manquantes pour la page reports/monthly
// Il met à jour les fichiers lang/fr/ui.php et lang/en/ui.php

// Pour appliquer les traductions, exécutez:
// cd /home/koyuki/drivncook && php add_monthly_translations.php

$frTranslations = [
    "sidebar" => [
        "reports" => "Rapports",
        "monthly_reports" => "Rapports Mensuels",
        "compliance_reports" => "Rapports de Conformité",
    ],
    "bo" => [
        "reports" => [
            "monthly" => [
                "title" => "Rapports Mensuels de Ventes",
                "subtitle" => "Visualiser et générer des rapports mensuels de ventes",
                "all_franchisees" => "Tous les Franchisés",
                "create_success" => "Rapport créé avec succès",
                "already_exists" => "Le rapport existe déjà pour cette période",
                "file_not_found" => "Fichier non trouvé",
                "generated_success" => "Rapport généré pour :franchisee — :month :year",
                "no_franchisee_selected" => "Aucun franchisé sélectionné",
                "filters" => [
                    "title" => "Filtres",
                    "franchisee" => "Franchisé",
                    "month" => "Mois",
                    "year" => "Année",
                    "all_franchisees" => "Tous les Franchisés",
                    "all_months" => "Tous les Mois",
                    "apply" => "Appliquer",
                    "reset" => "Réinitialiser",
                ],
                "table" => [
                    "title" => "Rapports Disponibles",
                    "franchisee" => "Franchisé",
                    "period" => "Période",
                    "generated_at" => "Généré le",
                    "actions" => "Actions",
                    "download" => "Télécharger",
                    "all_franchisees" => "Tous les Franchisés",
                ],
                "generate" => [
                    "title" => "Générer un Nouveau Rapport",
                    "button" => "Générer",
                    "franchisee" => "Franchisé",
                    "month" => "Mois",
                    "year" => "Année",
                    "all_franchisees" => "Tous les Franchisés",
                ],
                "empty" => [
                    "title" => "Aucun Rapport",
                    "description" => "Aucun rapport disponible pour les critères sélectionnés",
                    "generate_first" => "Générer le premier rapport",
                ],
            ],
        ],
    ],
];

$enTranslations = [
    "sidebar" => [
        "reports" => "Reports",
        "monthly_reports" => "Monthly Reports",
        "compliance_reports" => "Compliance Reports",
    ],
    "bo" => [
        "reports" => [
            "monthly" => [
                "title" => "Monthly Sales Reports",
                "subtitle" => "View and generate monthly sales reports",
                "all_franchisees" => "All Franchisees",
                "create_success" => "Report created successfully",
                "already_exists" => "Report already exists for this period",
                "file_not_found" => "File not found",
                "generated_success" => "Report generated for :franchisee — :month :year",
                "no_franchisee_selected" => "No franchisee selected",
                "filters" => [
                    "title" => "Filters",
                    "franchisee" => "Franchisee",
                    "month" => "Month",
                    "year" => "Year",
                    "all_franchisees" => "All Franchisees",
                    "all_months" => "All Months",
                    "apply" => "Apply",
                    "reset" => "Reset",
                ],
                "table" => [
                    "title" => "Available Reports",
                    "franchisee" => "Franchisee",
                    "period" => "Period",
                    "generated_at" => "Generated At",
                    "actions" => "Actions",
                    "download" => "Download",
                    "all_franchisees" => "All Franchisees",
                ],
                "generate" => [
                    "title" => "Generate New Report",
                    "button" => "Generate",
                    "franchisee" => "Franchisee",
                    "month" => "Month",
                    "year" => "Year",
                    "all_franchisees" => "All Franchisees",
                ],
                "empty" => [
                    "title" => "No Reports",
                    "description" => "No reports available for the selected criteria",
                    "generate_first" => "Generate First Report",
                ],
            ],
        ],
    ],
];

// Fonction récursive pour ajouter les traductions à un tableau existant
function arrayMergeRecursive(&$array1, $array2) {
    foreach ($array2 as $key => $value) {
        if (is_array($value)) {
            if (!isset($array1[$key]) || !is_array($array1[$key])) {
                $array1[$key] = array();
            }
            arrayMergeRecursive($array1[$key], $value);
        } else {
            $array1[$key] = $value;
        }
    }
}

// Mise à jour du fichier FR
$frFile = __DIR__ . '/lang/fr/ui.php';
$frContent = require $frFile;
arrayMergeRecursive($frContent, $frTranslations);
file_put_contents($frFile, "<?php\n\nreturn " . var_export($frContent, true) . ";\n");

// Mise à jour du fichier EN
$enFile = __DIR__ . '/lang/en/ui.php';
$enContent = require $enFile;
arrayMergeRecursive($enContent, $enTranslations);
file_put_contents($enFile, "<?php\n\nreturn " . var_export($enContent, true) . ";\n");

echo "Traductions mises à jour avec succès!\n";
