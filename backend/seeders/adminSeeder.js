const bcrypt = require('bcryptjs');
const { sequelize, User, Room, Item } = require('../models');

async function seed() {
    try {
        console.log("Connecting to the database and syncing tables...");
        // Sync models
        await sequelize.sync({ force: true });
        console.log("Database tables synchronized.");

        // Hash admin password
        const hashedPassword = await bcrypt.hash('admin123', 10);

        // Seed default admin
        const admin = await User.create({
            name: 'Administrator',
            email: 'admin@gmail.com',
            password: hashedPassword,
            role: 'admin'
        });
        console.log(`Default admin created: ${admin.email}`);

        // Seed some sample data for testing
        const room1 = await Room.create({
            room_name: 'Lab Komputer A',
            location: 'Gedung Baru Lantai 2',
            capacity: 40
        });
        const room2 = await Room.create({
            room_name: 'Lab Jaringan',
            location: 'Gedung Utama Lantai 1',
            capacity: 25
        });
        console.log("Sample rooms seeded.");

        await Item.create({
            item_name: 'PC All-in-One Dell',
            category: 'Elektronik',
            stock: 30,
            room_id: room1.id
        });
        await Item.create({
            item_name: 'Router Cisco Catalyst',
            category: 'Jaringan',
            stock: 5,
            room_id: room2.id
        });
        console.log("Sample items seeded.");

        console.log("Database seeding completed successfully!");
        process.exit(0);
    } catch (error) {
        console.error("Error seeding database:", error);
        process.exit(1);
    }
}

seed();
