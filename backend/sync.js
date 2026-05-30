const bcrypt = require('bcryptjs');
const { 
    sequelize, Role, User, Room, Item, BhpStock, Inventory, ProcurementDraft, ProcurementDetail 
} = require('./models');

async function syncAndSeed() {
    try {
        console.log("Menghubungkan ke database dan menyinkronkan struktur tabel...");
        
        // Nonaktifkan pemeriksaan foreign key untuk sinkronisasi ulang yang aman
        await sequelize.query('SET FOREIGN_KEY_CHECKS = 0', { raw: true });
        
        // Lakukan sinkronisasi struktur model ke database (menggunakan force: true untuk setup bersih ulang)
        await sequelize.sync({ force: true });
        
        await sequelize.query('SET FOREIGN_KEY_CHECKS = 1', { raw: true });
        console.log("Struktur database capstone2 berhasil diperbarui.");

        // 1. Seed Roles
        console.log("Seeding Roles...");
        const roles = await Role.bulkCreate([
            { id: 1, name: 'Administrator' },
            { id: 2, name: 'Kepala Laboratorium' },
            { id: 3, name: 'Ketua Program Studi' },
            { id: 4, name: 'Staf Administrasi' },
            { id: 5, name: 'Staf Laboratorium' }
        ]);
        console.log("Roles seeded successfully.");

        // Hash password
        const passwordHash = await bcrypt.hash('password', 10);

        // 2. Seed Users
        console.log("Seeding Users...");
        await User.bulkCreate([
            { name: 'Admin User', email: 'admin@example.com', password: passwordHash, role_id: 1 },
            { name: 'Kepala Lab', email: 'kalab@example.com', password: passwordHash, role_id: 2 },
            { name: 'Kaprodi', email: 'kaprodi@example.com', password: passwordHash, role_id: 3 },
            { name: 'Staf Admin', email: 'stafadmin@example.com', password: passwordHash, role_id: 4 },
            { name: 'Staf Lab', email: 'staflab@example.com', password: passwordHash, role_id: 5 }
        ]);
        console.log("Users seeded successfully.");

        // 3. Seed Rooms
        console.log("Seeding Rooms...");
        const room1 = await Room.create({ name: 'Lab Komputer 1', description: 'Laboratorium Komputer Dasar' });
        const room2 = await Room.create({ name: 'Lab Jaringan', description: 'Laboratorium Jaringan dan Keamanan' });
        console.log("Rooms seeded successfully.");

        // 4. Seed Items
        console.log("Seeding Items...");
        const item1 = await Item.create({ name: 'PC Desktop', type: 'inventory', description: 'PC untuk praktikum' });
        const item2 = await Item.create({ name: 'Router', type: 'inventory', description: 'Router Cisco' });
        const item3 = await Item.create({ name: 'Kabel UTP', type: 'bhp', description: 'Kabel jaringan per meter' });
        const item4 = await Item.create({ name: 'Konektor RJ45', type: 'bhp', description: 'Konektor ujung kabel' });
        const item5 = await Item.create({ name: 'Tinta Printer Hitam', type: 'bhp', description: 'Tinta untuk cetak laporan' });
        console.log("Items seeded successfully.");

        // 5. Initialize BHP Stocks
        console.log("Seeding BHP Stocks...");
        await BhpStock.bulkCreate([
            { item_id: item3.id, total_quantity: 100 },
            { item_id: item4.id, total_quantity: 200 },
            { item_id: item5.id, total_quantity: 5 }
        ]);
        console.log("BHP Stocks seeded successfully.");

        // 6. Sample Inventory
        console.log("Seeding Sample Inventories...");
        await Inventory.bulkCreate([
            { item_id: item1.id, room_id: room1.id, label_number: 'INV-PC-001', condition: 'good' },
            { item_id: item1.id, room_id: room1.id, label_number: 'INV-PC-002', condition: 'maintenance' },
            { item_id: item2.id, room_id: room2.id, label_number: 'INV-RT-001', condition: 'good' }
        ]);
        console.log("Sample Inventories seeded successfully.");

        console.log("\n==========================================");
        console.log("🚀 DATABASE SETUP & SEEDING SELESAI!");
        console.log("==========================================");
        console.log("Akun Login Default:");
        console.log("- Email    : admin@example.com");
        console.log("- Password : password");
        console.log("==========================================");
        
        process.exit(0);
    } catch (error) {
        console.error("Error saat seeding database:", error);
        process.exit(1);
    }
}

syncAndSeed();
