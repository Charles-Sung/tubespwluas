<?php
$files = glob('database/migrations/*_create_*.php');
$order = [
    'create_roles_table' => 1,
    'create_users_table' => 2,
    'create_rooms_table' => 3,
    'create_items_table' => 4,
    'create_procurement_drafts_table' => 5,
    'create_procurement_details_table' => 6,
    'create_inventories_table' => 7,
    'create_item_receipts_table' => 8,
    'create_bhp_stocks_table' => 9,
    'create_bhp_transactions_table' => 10,
    'create_maintenance_logs_table' => 11,
    'create_maintenance_bhp_usages_table' => 12,
];

foreach ($files as $file) {
    if (strpos($file, 'cache_table') !== false || strpos($file, 'jobs_table') !== false) {
        continue; // Keep as is
    }
    
    foreach ($order as $name => $index) {
        if (strpos($file, $name) !== false) {
            $newName = 'database/migrations/2026_01_01_' . sprintf('%06d', $index) . '_' . $name . '.php';
            if ($file !== $newName) {
                rename($file, $newName);
                echo "Renamed $file to $newName\n";
            }
        }
    }
}
