<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        $roles = [
            ['name' => 'Administrator'],
            ['name' => 'Kepala Laboratorium'],
            ['name' => 'Ketua Program Studi'],
            ['name' => 'Staf Administrasi'],
            ['name' => 'Staf Laboratorium'],
        ];
        \Illuminate\Support\Facades\DB::table('roles')->insert($roles);

        // Users
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role_id' => 1],
            ['name' => 'Kepala Lab', 'email' => 'kalab@example.com', 'password' => bcrypt('password'), 'role_id' => 2],
            ['name' => 'Kaprodi', 'email' => 'kaprodi@example.com', 'password' => bcrypt('password'), 'role_id' => 3],
            ['name' => 'Staf Admin', 'email' => 'stafadmin@example.com', 'password' => bcrypt('password'), 'role_id' => 4],
            ['name' => 'Staf Lab', 'email' => 'staflab@example.com', 'password' => bcrypt('password'), 'role_id' => 5],
        ];
        \Illuminate\Support\Facades\DB::table('users')->insert($users);

        // Rooms
        $rooms = [
            ['name' => 'Lab Komputer 1', 'description' => 'Laboratorium Komputer Dasar'],
            ['name' => 'Lab Jaringan', 'description' => 'Laboratorium Jaringan dan Keamanan'],
        ];
        \Illuminate\Support\Facades\DB::table('rooms')->insert($rooms);

        // Items
        $items = [
            ['name' => 'PC Desktop', 'type' => 'inventory', 'description' => 'PC untuk praktikum'],
            ['name' => 'Router', 'type' => 'inventory', 'description' => 'Router Cisco'],
            ['name' => 'Kabel UTP', 'type' => 'bhp', 'description' => 'Kabel jaringan per meter'],
            ['name' => 'Konektor RJ45', 'type' => 'bhp', 'description' => 'Konektor ujung kabel'],
            ['name' => 'Tinta Printer Hitam', 'type' => 'bhp', 'description' => 'Tinta untuk cetak laporan'],
        ];
        \Illuminate\Support\Facades\DB::table('items')->insert($items);

        // Initialize BHP Stocks
        \Illuminate\Support\Facades\DB::table('bhp_stocks')->insert([
            ['item_id' => 3, 'total_quantity' => 100], // 100 meter
            ['item_id' => 4, 'total_quantity' => 200], // 200 pcs
            ['item_id' => 5, 'total_quantity' => 5],   // 5 botol
        ]);

        // Sample Inventory
        \Illuminate\Support\Facades\DB::table('inventories')->insert([
            ['item_id' => 1, 'room_id' => 1, 'label_number' => 'INV-PC-001', 'condition' => 'good'],
            ['item_id' => 1, 'room_id' => 1, 'label_number' => 'INV-PC-002', 'condition' => 'maintenance'],
            ['item_id' => 2, 'room_id' => 2, 'label_number' => 'INV-RT-001', 'condition' => 'good'],
        ]);
    }
}
