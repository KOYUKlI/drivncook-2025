<?php

return [
    // Déploiements
    'deployment' => [
        'fields' => [
            'location' => 'Emplacement',
            'planned_start_at' => 'Début prévu',
            'planned_end_at' => 'Fin prévue',
            'actual_start_at' => 'Début effectif',
            'actual_end_at' => 'Fin effective',
            'planned_dates' => 'Dates prévues',
            'actual_dates' => 'Dates effectives',
            'status' => 'Statut',
            'date' => 'Date',
            'notes' => 'Notes',
            'franchisee' => 'Franchisé',
            'start_date' => 'Date de début',
            'end_date' => 'Date de fin',
            'start_time' => 'Heure de début',
            'end_time' => 'Heure de fin',
        ],
        'placeholder' => [
            'location' => 'Ex: Place du marché, Paris 15e',
            'notes' => 'Notes additionnelles pour ce déploiement...',
        ],
        'status' => [
            'planned' => 'Planifié',
            'open' => 'En cours',
            'closed' => 'Terminé',
            'cancelled' => 'Annulé',
        ],
        'actions' => [
            'schedule' => 'Planifier',
            'open' => 'Démarrer',
            'close' => 'Terminer',
            'cancel' => 'Annuler',
        ],
        'schedule_deployment' => 'Planifier un déploiement',
        'schedule_deployment_for' => 'Planifier un déploiement pour',
        'quick_select' => 'Sélection rapide',
        'same_day' => 'Même jour',
        'notes_placeholder' => 'Informations additionnelles sur ce déploiement...',
        'recent_info' => 'Dernier déploiement planifié',
        'no_deployments' => 'Aucun déploiement',
        'get_started' => 'Planifiez votre premier déploiement avec le bouton ci-dessus',
        'calendar' => 'Calendrier des déploiements',
        'upcoming' => 'Déploiements à venir',
        'sort_by' => 'Trier par',
        'sort_newest' => 'Date (plus récent)',
        'sort_oldest' => 'Date (plus ancien)',
        'sort_location' => 'Emplacement',
        'sort_status' => 'Statut',
    ],
    
    // Jours et mois
    'days' => [
        'mon' => 'Lun',
        'tue' => 'Mar',
        'wed' => 'Mer',
        'thu' => 'Jeu',
        'fri' => 'Ven',
        'sat' => 'Sam',
        'sun' => 'Dim',
    ],
    
    // Temps
    'times' => [
        'morning' => 'Matin (8h-12h)',
        'afternoon' => 'Après-midi (14h-18h)',
        'evening' => 'Soirée (18h-22h)',
        'all_day' => 'Journée (8h-22h)',
    ],
    
    // Divers
    'today' => 'Aujourd\'hui',
    'loading' => 'Chargement...',
    'actions' => [
        'new_deployment' => 'Nouveau déploiement',
    ],
    'cancel' => 'Annuler',
    'close' => 'Fermer',
];
