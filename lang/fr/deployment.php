<?php

return [
    // General
    'deployment' => 'Déploiement',
    'deployments' => 'Déploiements',
    'schedule_deployment' => 'Planifier un déploiement',
    'reschedule_deployment' => 'Replanifier le déploiement',
    'open_deployment' => 'Démarrer le déploiement',
    'close_deployment' => 'Terminer le déploiement',
    'cancel_deployment' => 'Annuler le déploiement',
    'open_deployment_info' => 'Vous êtes sur le point de démarrer ce déploiement. Veuillez confirmer la date de début réelle et les détails de l\'emplacement.',
    'close_deployment_info' => 'Vous êtes sur le point de terminer ce déploiement. Veuillez confirmer la date de fin réelle.',
    'cancel_deployment_warning' => 'Êtes-vous sûr de vouloir annuler ce déploiement ? Cette action ne peut pas être annulée.',
    
    // Fields
    'fields' => [
        'location' => 'Emplacement',
        'planned_start_at' => 'Date de début prévue',
        'planned_end_at' => 'Date de fin prévue',
        'planned_date_range' => 'Date de début prévue / Date de fin prévue',
        'actual_start_at' => 'Date de début réelle',
        'actual_end_at' => 'Date de fin réelle',
        'status' => 'Statut',
        'franchisee' => 'Franchisé',
        'notes' => 'Notes',
        'geo_lat' => 'Latitude',
        'geo_lng' => 'Longitude',
        'cancel_reason' => 'Motif d\'annulation',
        'utilization' => 'Taux d\'utilisation',
    ],
    
    // Statuses
    'status' => [
        'planned' => 'Planifié',
        'open' => 'En cours',
        'closed' => 'Terminé',
        'cancelled' => 'Annulé',
    ],
    
    // Actions
    'actions' => [
        'schedule' => 'Planifier',
        'program_deployment' => 'Programmer un déploiement',
        'reschedule' => 'Replanifier',
        'open' => 'Démarrer',
        'close' => 'Terminer',
        'cancel' => 'Annuler',
        'view' => 'Voir détails',
        'export' => 'Exporter en CSV',
        'filter' => 'Filtrer',
        'reset' => 'Réinitialiser les filtres',
    ],
    
    // Messages
    'messages' => [
        'scheduled' => 'Déploiement planifié avec succès.',
        'success' => 'Déploiement planifié avec succès.',
        'rescheduled' => 'Déploiement replanifié avec succès.',
        'opened' => 'Déploiement démarré avec succès.',
        'closed' => 'Déploiement terminé avec succès.',
        'cancelled' => 'Déploiement annulé avec succès.',
        'no_deployments' => 'Aucun déploiement trouvé.',
        'utilization_rate' => ':rate% d\'utilisation sur les 30 derniers jours',
        'utilization_info' => 'Cela indique le temps pendant lequel le camion a été activement déployé au cours du dernier mois.',
    ],
    
    // Errors
    'errors' => [
        'schedule_conflict' => 'Ce déploiement est en conflit avec un autre déploiement pour ce camion.',
        'truck_already_deployed' => 'Ce camion est déjà déployé pendant cette période.',
        'invalid_transition' => 'Transition de statut invalide.',
        'already_started' => 'Ce déploiement a déjà commencé.',
        'already_ended' => 'Ce déploiement est déjà terminé.',
        'already_cancelled' => 'Ce déploiement a déjà été annulé.',
    ],
    
    // Filters
    'filters' => [
        'title' => 'Filtrer les déploiements',
        'date_range' => 'Période',
        'status' => 'Statut',
        'franchisee' => 'Franchisé',
        'location' => 'Emplacement',
    ],
];
