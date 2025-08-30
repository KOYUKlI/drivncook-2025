<?php

return [
    // General
    'deployment' => 'Deployment',
    'deployments' => 'Deployments',
    'schedule_deployment' => 'Schedule Deployment',
    'reschedule_deployment' => 'Reschedule Deployment',
    'open_deployment' => 'Open Deployment',
    'close_deployment' => 'Close Deployment',
    'cancel_deployment' => 'Cancel Deployment',
    'open_deployment_info' => 'You are about to start this deployment. Please confirm the actual start date and location details.',
    'close_deployment_info' => 'You are about to end this deployment. Please confirm the actual end date.',
    'cancel_deployment_warning' => 'Are you sure you want to cancel this deployment? This action cannot be undone.',
    
    // Fields
    'fields' => [
        'location' => 'Location',
        'planned_start_at' => 'Planned Start Date',
        'planned_end_at' => 'Planned End Date',
        'actual_start_at' => 'Actual Start Date',
        'actual_end_at' => 'Actual End Date',
        'status' => 'Status',
        'franchisee' => 'Franchisee',
        'notes' => 'Notes',
        'geo_lat' => 'Latitude',
        'geo_lng' => 'Longitude',
        'cancel_reason' => 'Cancellation Reason',
        'utilization' => 'Truck Utilization',
    ],
    
    // Statuses
    'status' => [
        'planned' => 'Planned',
        'open' => 'Active',
        'closed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],
    
    // Actions
    'actions' => [
        'schedule' => 'Schedule',
        'reschedule' => 'Reschedule',
        'open' => 'Start',
        'close' => 'End',
        'cancel' => 'Cancel',
        'view' => 'View Details',
        'export' => 'Export to CSV',
        'filter' => 'Filter',
        'reset' => 'Reset Filters',
    ],
    
    // Messages
    'messages' => [
        'scheduled' => 'Deployment scheduled successfully.',
        'rescheduled' => 'Deployment rescheduled successfully.',
        'opened' => 'Deployment started successfully.',
        'closed' => 'Deployment ended successfully.',
        'cancelled' => 'Deployment cancelled successfully.',
        'no_deployments' => 'No deployments found.',
        'utilization_rate' => ':rate% utilization over the last 30 days',
        'utilization_info' => 'This shows how much the truck has been actively deployed in the last month.',
    ],
    
    // Errors
    'errors' => [
        'schedule_conflict' => 'This deployment conflicts with another deployment for this truck.',
        'truck_already_deployed' => 'This truck is already deployed during this time period.',
        'invalid_transition' => 'Invalid status transition.',
        'already_started' => 'This deployment has already started.',
        'already_ended' => 'This deployment has already ended.',
        'already_cancelled' => 'This deployment has already been cancelled.',
    ],
    
    // Filters
    'filters' => [
        'title' => 'Filter Deployments',
        'date_range' => 'Date Range',
        'status' => 'Status',
        'franchisee' => 'Franchisee',
        'location' => 'Location',
    ],
];
