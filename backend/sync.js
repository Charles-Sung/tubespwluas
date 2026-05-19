const bcrypt = require('bcryptjs');
const { 
    sequelize, Role, User, Room, Item, BhpStock, Inventory 
} = require('./models');

async function syncAndSeed() {
    try {
        await sequelize.query('SET FOREIGN_KEY_CHECKS = 0', { raw: true });
        await sequelize.sync({ force: true });
        await sequelize.query('SET FOREIGN_KEY_CHECKS = 1', { raw: true });
        console.log("Database synced successfully.");

        // Roles
        await Role.bulkCreate([
            { id: 1, name: 'Administrator' },
            { id: 2, name: 'Kepala Laboratorium' },
            { id: 3, name: 'Ketua Program Studi' },
            { id: 4, name: 'Staf Administrasi' },
            { id: 5, name: 'Staf Laboratorium' }
        ]);

        const password = await bcrypt.hash('password', 10);

        // Users
        await User.bulkCreate([
            { name: 'Admin User', email: 'admin@example.com', password, role_id: 1 },
            { name: 'Kepala Lab', email: 'kalab@example.com', password, role_id: 2 },
            { name: 'Kaprodi', email: 'kaprodi@example.com', password, role_id: 3 },
            { name: 'Staf Admin', email: 'stafadmin@example.com', password, role_id: 4 },
            { name: 'Staf Lab', email: 'staflab@example.com', password, role_id: 5 }
        ]);

        // Rooms
        await Room.bulkCreate([
            { id: 1, name: 'Lab Komputer 1', description: 'Laboratorium Komputer Dasar' },
            { id: 2, name: 'Lab Jaringan', description: 'Laboratorium Jaringan dan Keamanan' }
        ]);

        // Items
        await Item.bulkCreate([
            { id: 1, name: 'PC Desktop', type: 'inventory', description: 'PC untuk praktikum' },
            { id: 2, name: 'Router', type: 'inventory', description: 'Router Cisco' },
            { id: 3, name: 'Kabel UTP', type: 'bhp', description: 'Kabel jaringan per meter' },
            { id: 4, name: 'Konektor RJ45', type: 'bhp', description: 'Konektor ujung kabel' },
            { id: 5, name: 'Tinta Printer Hitam', type: 'bhp', description: 'Tinta untuk cetak laporan' }
        ]);

        // BHP Stocks
        await BhpStock.bulkCreate([
            { item_id: 3, total_quantity: 100 },
            { item_id: 4, total_quantity: 200 },
            { item_id: 5, total_quantity: 5 }
        ]);

        // Inventories
        await Inventory.bulkCreate([
            { item_id: 1, room_id: 1, label_number: 'INV-PC-001', condition: 'good' },
            { item_id: 1, room_id: 1, label_number: 'INV-PC-002', condition: 'maintenance' },
            { item_id: 2, room_id: 2, label_number: 'INV-RT-001', condition: 'good' }
        ]);

        console.log("Seed data inserted successfully.");
        process.exit();
    } catch (error) {
        console.error("Error syncing database:", error);
        process.exit(1);
    }
}

syncAndSeed();
