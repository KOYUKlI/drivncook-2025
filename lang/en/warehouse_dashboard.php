<?php

return [
    // Inventory Dashboard Translations
    'inventory' => [
        'view_inventory' => 'View Inventory',
        'back_to_warehouses' => 'Back to Warehouses',
        'dashboard' => [
            'title' => ':warehouse Dashboard',
            'menu_title' => 'Dashboard',
            'kpis' => [
                'active_items' => 'Active Items',
                'low_stock' => 'Low Stock Items',
                'movements_7days' => 'Movements (7d)',
                'movements_30days' => 'Movements (30d)',
                'po_received_30days' => 'POs Received (30d)'
            ],
            'recent_movements' => 'Recent Stock Movements',
            'no_movements' => 'No movements found for the selected period',
            'filters' => [
                'from_date' => 'From Date',
                'to_date' => 'To Date',
                'movement_type' => 'Movement Type',
                'all_types' => 'All Types',
                'apply' => 'Apply Filters',
                'reset' => 'Reset'
            ],
            'table' => [
                'date' => 'Date',
                'type' => 'Type',
                'item' => 'Item',
                'quantity' => 'Quantity',
                'user' => 'User',
                'reason' => 'Reason'
            ],
            'export' => [
                'button' => 'Export to CSV',
                'date' => 'Date',
                'type' => 'Type',
                'item' => 'Item',
                'quantity' => 'Quantity',
                'user' => 'User',
                'reason' => 'Reason'
            ]
        ],
        'movement_types' => [
            'receipt' => 'Receipt',
            'withdrawal' => 'Withdrawal',
            'adjustment' => 'Adjustment',
            'transfer_in' => 'Transfer In',
            'transfer_out' => 'Transfer Out'
        ]
    ]
];
