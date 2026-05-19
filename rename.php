<?php
$files = glob('database/migrations/2026_*_create_roles_table.php');
if (!empty($files)) {
    rename($files[0], 'database/migrations/0000_01_01_000000_create_roles_table.php');
}
